<?php

namespace App\Http\Controllers\stock;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class StockIndexController extends Controller
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
        $tanggal = request()->tanggal ?? date('Y-m-d');
        $kemarin = date('Y-m-d', strtotime($tanggal . ' -1 day'));
        $bulan = date('m', strtotime($tanggal));
        $tahun = date('Y', strtotime($tanggal));

        $query   = request('query');

        $stock = DB::table('parts')
            ->leftJoin(DB::raw("(
                    SELECT id, part_id, vendor_id, tanggal, fg, wip, rm, judgement, kategori_problem, detail_problem
                    FROM master_stock
                    WHERE tanggal = '$tanggal'
                ) AS ms
            "), function ($join) {
                $join->on('parts.id', '=', 'ms.part_id');
            })

            ->leftJoin('master_2hk', 'parts.id', '=', 'master_2hk.part_id')

            ->leftJoin(DB::raw("(
                SELECT part_id,
                    vendor_id,
                    SUM(qty_po) AS qty_po,
                    SUM(qty_outstanding) AS qty_outstanding
                FROM po_table
                WHERE MONTH(delivery_date) = $bulan
                AND YEAR(delivery_date) = $tahun
                GROUP BY part_id, vendor_id
            ) po"), 'parts.id', '=', 'po.part_id')

            ->leftJoin('vendors', function ($join) {
                $join->on('vendors.id', '=', DB::raw('COALESCE(ms.vendor_id, po.vendor_id)'));
            })

            ->leftJoin(DB::raw("(
                SELECT 
                    d.part_id,
                    p.vendor_id,
                    SUM(d.qty_plan) AS qty_plan,
                    SUM(d.qty_delivery) AS qty_delivery,
                    SUM(d.balance) AS balance,
                    SUM(d.qty_delay) AS qty_delay,
                    SUM(d.qty_manifest) AS qty_manifest
                FROM master_di d
                JOIN po_table p ON d.po_id = p.id
                WHERE MONTH(d.delivery_date) = $bulan
                AND YEAR(d.delivery_date) = $tahun
                AND DATE(d.delivery_date) <= '$kemarin'
                GROUP BY d.part_id, p.vendor_id
            ) di"), function ($join) {
                $join->on('parts.id', '=', 'di.part_id');
                $join->on(DB::raw('COALESCE(ms.vendor_id, po.vendor_id)'), '=', 'di.vendor_id');
            })

            ->select(
                'parts.id',
                'parts.item_code',
                'parts.part_name',

                DB::raw('COALESCE(ms.vendor_id, po.vendor_id) as vendor_id'),
                'vendors.nickname',

                'ms.id as stock_id',
                'ms.tanggal',
                'ms.fg',
                'ms.wip',
                'ms.rm',
                'ms.judgement',
                'ms.kategori_problem',
                'ms.detail_problem',

                DB::raw('COALESCE(po.qty_po, 0) as qty_po'),
                DB::raw('COALESCE(po.qty_outstanding, 0) as qty_outstanding'),
                DB::raw('COALESCE(di.qty_plan, 0) as qty_plan'),
                DB::raw('COALESCE(di.qty_delivery, 0) as qty_delivery'),
                DB::raw('COALESCE(di.balance, 0) as balance'),
                DB::raw('COALESCE(di.qty_delay, 0) as qty_delay'),
                DB::raw('COALESCE(di.qty_manifest, 0) as qty_manifest'),
                DB::raw('MAX(master_2hk.std_stock) as std_stock')
            )

            ->groupBy(
                'parts.id',
                'parts.item_code',
                'parts.part_name',
                'ms.vendor_id',
                'po.vendor_id',
                'vendors.nickname',
                'ms.id',
                'ms.tanggal',
                'ms.fg',
                'ms.wip',
                'ms.rm',
                'ms.judgement',
                'ms.kategori_problem',
                'ms.detail_problem',
                'po.qty_po',
                'po.qty_outstanding',
                'di.qty_plan',
                'di.qty_delivery',
                'di.balance',
                'di.qty_delay',
                'di.qty_manifest'
            )
            ->orderBy('vendors.nickname', 'asc');

        if (Auth::user()->role === 'vendor') {
            $stock->where('ms.vendor_id', Auth::user()->vendor_id);
        }

        if ($query) {
            $stock->where(function ($q) use ($query) {
                $q->where('parts.item_code', 'like', "%$query%")
                    ->orWhere('parts.part_name', 'like', "%$query%")
                    ->orWhere('vendors.nickname', 'like', "%$query%")
                    ->orWhere('ms.judgement', 'like', "%$query%")
                    ->orWhere('ms.kategori_problem', 'like', "%$query%")
                    ->orWhere('ms.detail_problem', 'like', "%$query%")
                    ->orWhere('po.qty_po', 'like', "%$query%");
            });
        }

        $stock->whereNotNull(DB::raw('COALESCE(ms.vendor_id, po.vendor_id)'));

        $stock = $stock->get();

        return view('stock.index', compact('tanggal', 'stock', 'query'));
    }
}
