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
        if ($fg < $std) return 'NG';
        if ($fg >= $std) return 'OK';

        return '-';
    }

    public function index()
    {
        $tanggal  = request()->tanggal ?? date('Y-m-d');
        $kemarin  = date('Y-m-d', strtotime($tanggal . ' -1 day'));
        $bulan    = date('m', strtotime($tanggal));
        $tahun    = date('Y', strtotime($tanggal));
        $query    = request('query');

        // Ambil stock sama seperti StockIndexController
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
                    SUM(CASE WHEN d.qty_delivery = 0 THEN 1 ELSE 0 END) AS qty_delivery,
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
                  AND DATE(d.delivery_date) <= '$kemarin'
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
                DB::raw('COALESCE(di.qty_plan,0) as qty_plan'),
                DB::raw('COALESCE(di.qty_delivery,0) as qty_delivery'),
                DB::raw('COALESCE(di.balance,0) as balance'),
                DB::raw('COALESCE(di.qty_delay,0) as qty_delay'),
                DB::raw('COALESCE(di.qty_manifest,0) as qty_manifest'),
                'hk.std_stock'
            )
            ->orderBy('v.nickname', 'asc');

        if (Auth::user()->role === 'vendor') {
            $stock->where('pv.vendor_id', Auth::user()->vendor_id);
        }

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

        $allStock = $stock->get();

        foreach ($allStock as $row) {
            $row->judgement = $this->calcJudgement($row);
        }

        // Pie chart data: NG vs OK per vendor
        $pieData = $allStock->groupBy('nickname')->map(function ($items) {
            $ok  = $items->where('judgement', 'OK')->count();
            $ng  = $items->where('judgement', 'NG')->count();
            return ['OK' => $ok, 'NG' => $ng];
        });

        $stock = $allStock->where('judgement', 'NG')->values();

        return view('monitoring.stock', compact('tanggal', 'stock', 'pieData', 'query'));
    }
}
