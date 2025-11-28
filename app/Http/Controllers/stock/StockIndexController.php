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

        $stock = DB::table('master_stock')
            ->whereDate('tanggal', $tanggal)
            ->leftJoin('parts', 'master_stock.part_id', '=', 'parts.id')
            ->leftJoin('vendors', 'master_stock.vendor_id', '=', 'vendors.id')
            ->leftJoin('master_2hk', 'parts.id', '=', 'master_2hk.part_id')
            ->leftJoin('po_table', function ($join) {
                $join->on('parts.id', '=', 'po_table.part_id')
                    ->on('master_stock.vendor_id', '=', 'po_table.vendor_id')
                    ->whereMonth('po_table.delivery_date', date('m'))
                    ->whereYear('po_table.delivery_date', date('Y'));
            })
            ->leftJoin('master_di', function ($join) {
                $join->on('po_table.id', '=', 'master_di.po_id')
                    ->whereMonth('master_di.delivery_date', date('m'))
                    ->whereYear('master_di.delivery_date', date('Y'));
            })
            ->select(
                'master_stock.*',
                'vendors.nickname',
                'parts.item_code',
                'parts.part_name',
                DB::raw('SUM(po_table.qty_po) as qty_po'),
                DB::raw('SUM(po_table.qty_outstanding) as qty_outstanding'),
                DB::raw('SUM(master_di.qty_plan) as qty_plan'),
                DB::raw('SUM(master_di.qty_delivery) as qty_delivery'),
                DB::raw('SUM(master_di.balance) as balance'),
                DB::raw('MAX(master_2hk.std_stock) as std_stock')
            )

            ->groupBy('master_stock.id');

        if (Auth::user()->role === 'vendor') {
            $stock->where('master_stock.vendor_id', Auth::user()->vendor_id);
        }

        if ($query) {
            $stock->where(function ($q) use ($query) {
                $q->where('parts.item_code', 'like', "%$query%")
                    ->orWhere('parts.part_name', 'like', "%$query%")
                    ->orWhere('vendors.nickname', 'like', "%$query%")
                    ->orWhere('master_stock.judgement', 'like', "%$query%")
                    ->orWhere('po_table.qty_po', 'like', "%$query%")
                    ->orWhere('master_2hk.std_stock', 'like', "%$query%");
            });
        }

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
