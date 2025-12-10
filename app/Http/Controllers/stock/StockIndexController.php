<?php

namespace App\Http\Controllers\stock;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class StockIndexController extends Controller
{
    public function index()
    {
        $tanggal = request()->tanggal ?? date('Y-m-d');
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

            ->leftJoin('po_table', function ($join) {
                $join->on('parts.id', '=', 'po_table.part_id')
                    ->on(DB::raw('COALESCE(ms.vendor_id, po_table.vendor_id)'), '=', 'po_table.vendor_id')
                    ->whereMonth('po_table.delivery_date', date('m'))
                    ->whereYear('po_table.delivery_date', date('Y'));
            })

            ->leftJoin('vendors', function ($join) {
                $join->on('vendors.id', '=', DB::raw('COALESCE(ms.vendor_id, po_table.vendor_id)'));
            })

            ->leftJoin('master_di', function ($join) {
                $join->on('po_table.id', '=', 'master_di.po_id')
                    ->whereMonth('master_di.delivery_date', date('m'))
                    ->whereYear('master_di.delivery_date', date('Y'));
            })

            ->select(
                'parts.id',
                'parts.item_code',
                'parts.part_name',

                DB::raw('COALESCE(ms.vendor_id, po_table.vendor_id) as vendor_id'),
                'vendors.nickname',

                'ms.id as stock_id',
                'ms.tanggal',
                'ms.fg',
                'ms.wip',
                'ms.rm',
                'ms.judgement',
                'ms.kategori_problem',
                'ms.detail_problem',

                DB::raw('SUM(po_table.qty_po) as qty_po'),
                DB::raw('SUM(po_table.qty_outstanding) as qty_outstanding'),
                DB::raw('SUM(master_di.qty_plan) as qty_plan'),
                DB::raw('SUM(master_di.qty_delivery) as qty_delivery'),
                DB::raw('SUM(master_di.balance) as balance'),
                DB::raw('MAX(master_2hk.std_stock) as std_stock')
            )
            ->groupBy(
                'parts.id',
                'parts.item_code',
                'parts.part_name',
                DB::raw('COALESCE(ms.vendor_id, po_table.vendor_id)'),
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
                    ->orWhere('po_table.qty_po', 'like', "%$query%");
            });
        }
        $stock->whereNotNull('vendors.id');

        $stock = $stock->get();

        foreach ($stock as $s) {
            $po = $s->qty_po ?? 0;
            $fg = $s->fg ?? 0;
            $std = $s->std_stock ?? 0;

            if ($po == 0) {
                $s->judgement = "NO PO";
            } elseif ($fg >= $std) {
                $s->judgement = "OK";
            } else {
                $s->judgement = "NG";
            }
        }

        return view('stock.index', compact('tanggal', 'stock', 'query'));
    }
}
