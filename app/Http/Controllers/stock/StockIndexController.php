<?php

namespace App\Http\Controllers\stock;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

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
            ->leftJoin('po_table', 'parts.id', '=', 'po_table.part_id')
            ->leftJoin('master_di', 'po_table.id', '=', 'master_di.po_id')
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

            ->when($query, function ($q) use ($query) {
                $q->where(function ($sub) use ($query) {
                    $sub->where('parts.item_code', 'like', "%$query%")
                        ->orWhere('parts.part_name', 'like', "%$query%")
                        ->orWhere('vendors.nickname', 'like', "%$query%")
                        ->orWhere('master_stock.judgement', 'like', "%$query%")
                        ->orWhere('po_table.qty_po', 'like', "%$query%")
                        ->orWhere('master_2hk.std_stock', 'like', "%$query%");
                });
            })

            ->groupBy('master_stock.id')
            ->get();

        return view('stock.index', compact('tanggal', 'stock', 'query'));
    }
}
