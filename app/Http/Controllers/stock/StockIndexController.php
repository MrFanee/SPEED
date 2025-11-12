<?php

namespace App\Http\Controllers\stock;

use App\Http\Controllers\Controller;
use App\Stock;
use Illuminate\Support\Facades\DB;

class StockIndexController extends Controller
{
    public function index()
    {
        $today = now()->toDateString();

        $stock = DB::table('master_stock')
            ->whereDate('tanggal', $today)
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
                'po_table.qty_po',
                'po_table.qty_outstanding',
                'master_di.qty_plan',
                'master_di.qty_delivery',
                'master_di.balance',
                'master_2hk.std_stock'
            )->get();
        return view('stock.index', compact('stock'));
    }
}
