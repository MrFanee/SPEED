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
                        po_table.part_id,
                        po_table.vendor_id,
                        SUM(master_di.qty_plan) AS qty_plan,
                        SUM(master_di.qty_delivery) AS qty_delivery,
                        SUM(master_di.balance) AS balance
                    FROM master_di
                    JOIN po_table ON master_di.po_id = po_table.id
                    WHERE MONTH(master_di.delivery_date) = $bulan
                    AND YEAR(master_di.delivery_date) = $tahun
                    GROUP BY po_table.part_id, po_table.vendor_id
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

                DB::raw('MAX(COALESCE(po.qty_po, 0)) as qty_po'),
                DB::raw('MAX(COALESCE(po.qty_outstanding, 0)) as qty_outstanding'),
                DB::raw('MAX(COALESCE(di.qty_plan, 0)) as qty_plan'),
                DB::raw('MAX(COALESCE(di.qty_delivery, 0)) as qty_delivery'),
                DB::raw('MAX(COALESCE(di.balance, 0)) as balance'),
                DB::raw('MAX(master_2hk.std_stock) as std_stock')
            )
            ->groupBy(
                'parts.id',
                'parts.item_code',
                'parts.part_name',
                DB::raw('COALESCE(ms.vendor_id, po.vendor_id)'),
                'vendors.nickname',
                'ms.id',
                'ms.tanggal',
                'ms.fg',
                'ms.wip',
                'ms.rm',
                'ms.judgement',
                'ms.kategori_problem',
                'ms.detail_problem'
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

        // foreach ($stock as $s) {
        //     if (!$s->stock_id) {
        //         $newID = DB::table('master_stock')->insertGetId([
        //             'part_id'         => $s->id,
        //             'vendor_id'       => $s->vendor_id,
        //             'tanggal'         => $tanggal,
        //             'fg'              => 0,
        //             'wip'             => 0,
        //             'rm'              => 0,
        //             'judgement'       => '-',
        //             'kategori_problem' => null,
        //             'detail_problem'  => null,
        //             'created_at'      => now(),
        //             'updated_at'      => now()
        //         ]);

        //         $s->stock_id = $newID;
        //         $s->fg = 0;
        //         $s->wip = 0;
        //         $s->rm = 0;
        //         $s->judgement = '-';
        //     }

        //     $newJudge = $this->calcJudgement($s);
        //     $s->judgement = $newJudge;
        //     if ($s->stock_id) {
        //         DB::table('master_stock')
        //             ->where('id', $s->stock_id)
        //             ->update(['judgement' => $newJudge]);
        //     }
        // }


        return view('stock.index', compact('tanggal', 'stock', 'query'));
    }
}
