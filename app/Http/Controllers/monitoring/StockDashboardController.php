<?php

namespace App\Http\Controllers\monitoring;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class StockDashboardController extends Controller
{
    private function calcJudgement($row)
    {
        $qty_po = $row->qty_po ?? 0;
        $fg     = $row->fg ?? 0;
        $std    = $row->std_stock ?? 0;

        if ($qty_po == 0) return 'NO PO';
        if ($fg < $std && $std > 0) return 'NG';
        if ($fg >= $std && $std > 0) return 'OK';

        return 'NG';
    }

    public function index()
    {
        $tanggal  = request()->tanggal ?? date('Y-m-d');
        $kemarin  = date('Y-m-d', strtotime($tanggal . ' -1 day'));
        $bulan    = date('m', strtotime($tanggal));
        $tahun    = date('Y', strtotime($tanggal));
        $query    = request('query');
        $vendor   = request('vendor');

        $lastDiDate = DB::table('master_di')
            ->whereDate('delivery_date', '<', $tanggal)
            ->max(DB::raw('DATE(delivery_date)'));

        $stock = DB::table('parts as p')
            ->join(DB::raw("(
                SELECT DISTINCT part_id, vendor_id FROM po_table
                UNION
                SELECT DISTINCT part_id, vendor_id FROM master_stock
            ) pv"), function ($join) {
                $join->on('p.id', '=', 'pv.part_id');
            })
            ->leftJoin('vendors as v', 'v.id', '=', 'pv.vendor_id')
            ->leftJoin('master_stock as ms', function ($join) use ($tanggal) {
                $join->on('ms.part_id', '=', 'pv.part_id');
                $join->on('ms.vendor_id', '=', 'pv.vendor_id');
                $join->where('ms.tanggal', '=', $tanggal);
            })
            ->leftJoin(DB::raw("(
                SELECT part_id, MAX(std_stock) AS std_stock
                FROM master_2hk
                GROUP BY part_id
            ) hk"), 'hk.part_id', '=', 'p.id')
            ->leftJoin(DB::raw("(
                SELECT part_id, vendor_id,
                       SUM(qty_po) AS qty_po,
                       SUM(qty_outstanding) AS qty_outstanding
                FROM po_table
                WHERE MONTH(delivery_date) = $bulan
                  AND YEAR(delivery_date) = $tahun
                GROUP BY part_id, vendor_id
            ) po"), function ($join) {
                $join->on('po.part_id', '=', 'pv.part_id');
                $join->on('po.vendor_id', '=', 'pv.vendor_id');
            })
            ->leftJoin(DB::raw("(
                SELECT d.part_id, p.vendor_id,
                    SUM(CASE WHEN d.qty_plan > 0 THEN 1 ELSE 0 END) AS qty_plan,
                    SUM(CASE WHEN d.qty_delivery > 0 THEN 1 ELSE 0 END) AS di_delay,
                    SUM(CASE WHEN d.qty_delivery = 0 THEN 1 ELSE 0 END) AS di_closed,
                    SUM(d.qty_manifest) AS qty_manifest,
                    (
                        SUM(CASE WHEN d.qty_plan > 0 THEN 1 ELSE 0 END)
                        - SUM(CASE WHEN d.qty_delivery = 0 THEN 1 ELSE 0 END)
                    ) AS qty_delay,
                    CASE 
                        WHEN SUM(CASE WHEN d.qty_plan > 0 THEN 1 ELSE 0 END) > 0
                        THEN ROUND(
                            SUM(CASE WHEN d.qty_delivery > 0 THEN 1 ELSE 0 END)
                            / SUM(CASE WHEN d.qty_plan > 0 THEN 1 ELSE 0 END) * 100, 1
                        )
                        ELSE 0
                    END AS balance
                FROM master_di d
                JOIN po_table p ON d.po_id = p.id
                WHERE MONTH(d.delivery_date) = $bulan
                AND YEAR(d.delivery_date) = $tahun
                AND DATE(d.delivery_date) <= '$lastDiDate'
                GROUP BY d.part_id, p.vendor_id
            ) di"), function ($join) {
                $join->on('di.part_id', '=', 'pv.part_id');
                $join->on('di.vendor_id', '=', 'pv.vendor_id');
            })
            ->select(
                'p.id',
                'p.item_code',
                'p.part_name',
                'pv.vendor_id',
                'v.nickname',
                'ms.id as stock_id',
                'ms.tanggal',
                'ms.fg',
                'ms.wip',
                'ms.rm',
                'ms.judgement',
                'ms.kategori_problem',
                'ms.detail_problem',
                DB::raw('COALESCE(po.qty_po,0) as qty_po'),
                DB::raw('COALESCE(po.qty_outstanding,0) as qty_outstanding'),
                DB::raw('COALESCE(di.qty_delay,0) as qty_delay'),
                DB::raw('COALESCE(di.di_delay,0) as di_delay'),
                DB::raw('COALESCE(di.di_closed,0) as di_closed'),
                DB::raw('COALESCE(di.qty_manifest,0) as qty_manifest'),
                'hk.std_stock'
            )
            ->orderBy('v.nickname', 'asc');

        if ($query) {
            $stock->where(function ($q) use ($query) {
                $q->where('p.item_code', 'like', "%$query%")
                    ->orWhere('p.part_name', 'like', "%$query%")
                    ->orWhere('v.nickname', 'like', "%$query%")
                    ->orWhere('ms.judgement', 'like', "%$query%")
                    ->orWhere('ms.kategori_problem', 'like', "%$query%")
                    ->orWhere('ms.detail_problem', 'like', "%$query%");
            });
        }

        if ($vendor) {
            $stock->where('v.nickname', $vendor);
        }

        $allStock = $stock->get();

        foreach ($allStock as $row) {
            $row->judgement = $this->calcJudgement($row);
        }

        $pieData = $allStock->groupBy('nickname')->map(function ($items) {
            $ok  = $items->where('judgement', 'OK')->count();
            $ng  = $items->where('judgement', 'NG')->count();
            return ['OK' => $ok, 'NG' => $ng];
        });

        $barData = $allStock->groupBy('nickname')->map(function ($items) {
    return [
        'delay'  => $items->sum('di_delay'),
        'closed' => $items->sum('di_closed'),
    ];
});


        $vendorList = DB::table('vendors')
            ->select('nickname')
            ->orderBy('nickname')
            ->get();

        $stock = $allStock->filter(function ($item) {
            return $item->judgement === 'NG' || $item->qty_delay > 0 && $item->qty_po > 0;
        })->values();

        return view('monitoring.stock', compact('tanggal', 'stock', 'pieData', 'barData', 'query', 'vendorList'));
    }
}
