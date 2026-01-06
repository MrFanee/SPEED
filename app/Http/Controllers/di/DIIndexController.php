<?php

namespace App\Http\Controllers\di;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;


class DIIndexController extends Controller
{
    public function index(Request $request)
    {
        
        $tahunList = DB::table('master_di')
            ->select(DB::raw('YEAR(delivery_date) as tahun'))
            ->distinct()
            ->orderBy('tahun', 'desc')
            ->pluck('tahun');

        $tahun = $request->get('tahun');
        if (!$tahun) {
            $tahun = DB::table('master_di')
                ->max(DB::raw('YEAR(delivery_date)'));
        }

        $bulanList = DB::table('master_di')
            ->select(DB::raw('MONTH(delivery_date) as bulan'))
            ->whereYear('delivery_date', $tahun)
            ->distinct()
            ->orderBy('bulan', 'desc')
            ->pluck('bulan');

        $bulan = $request->get('bulan');
        if (!$bulan) {
            $bulan = DB::table('master_di')
                ->whereYear('delivery_date', $tahun)
                ->max(DB::raw('MONTH(delivery_date)'));
        }

        $di = DB::table('master_di')
            ->leftJoin('po_table', 'master_di.po_id', '=', 'po_table.id')
            ->leftJoin('parts', 'master_di.part_id', '=', 'parts.id')
            ->leftJoin('vendors', 'po_table.vendor_id', '=', 'vendors.id')
            ->select(
                'master_di.id',
                'master_di.delivery_date',
                'parts.item_code',
                'parts.part_name',
                'po_table.po_number',
                'master_di.qty_plan',
                'master_di.qty_delivery',
                'master_di.balance'
            )
            ->whereMonth('master_di.delivery_date', $bulan)
            ->whereYear('master_di.delivery_date', $tahun);

        if (Auth::user()->role === 'vendor') {
            $di->where('po_table.vendor_id', Auth::user()->vendor_id);
        }

        $di = $di->get();

        return view('di.index', compact(
            'di',
            'bulan',
            'bulanList',
            'tahunList',
            'tahun'
        ));
    }
}
