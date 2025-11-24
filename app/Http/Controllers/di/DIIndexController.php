<?php

namespace App\Http\Controllers\di;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class DIIndexController extends Controller
{
    public function index(Request $request)
    {
        $query = $request->query('query');
        
        $di = DB::table('master_di')
            ->leftJoin('po_table', 'master_di.po_id', '=', 'po_table.id')
            ->leftJoin('parts', 'po_table.part_id', '=', 'parts.id')
            ->select(
                DB::raw('MAX(master_di.id) as id'),
                'parts.item_code',
                'parts.part_name',
                // DB::raw('SUM(po_table.qty_po) as qty_po'),
                // DB::raw('SUM(po_table.qty_outstanding) as qty_outstanding'),
                DB::raw('SUM(master_di.qty_plan) as qty_plan'),
                DB::raw('SUM(master_di.qty_delivery) as qty_delivery'),
                DB::raw('SUM(master_di.balance) as balance')
            )
            ->groupBy('parts.item_code', 'parts.part_name');
            
        if ($query) {
            $search = $request->query('query');

            $di->where(function ($q) use ($search) {
                $q->where('parts.item_code', 'like', "%$search%")
                    ->orWhere('parts.part_name', 'like', "%$search%");
            });
        }

        $di = $di->get();

        return view('di.index', compact('di'));
    }
}
