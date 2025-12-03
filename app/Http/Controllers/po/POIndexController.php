<?php

namespace App\Http\Controllers\po;

use App\Http\Controllers\Controller;
use App\PO;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class POIndexController extends Controller
{
    public function index(Request $request)
    {
        $tahunList = DB::table('po_table')
            ->select(DB::raw('YEAR(delivery_date) as tahun'))
            ->distinct()
            ->orderBy('tahun', 'desc')
            ->pluck('tahun');

        $tahun = $request->get('tahun', $tahunList->first());

        $bulanList = DB::table('po_table')
            ->select(DB::raw('MONTH(delivery_date) as bulan'))
            ->whereYear('delivery_date', $tahun)
            ->distinct()
            ->orderBy('bulan', 'desc')
            ->pluck('bulan');

        $bulan = $request->get('bulan', $bulanList->first());

        $po = PO::with('vendor', 'part')
            ->whereMonth('delivery_date', $bulan)
            ->whereYear('delivery_date', $tahun)
            ->orderBy('po_number', 'asc');

        if (Auth::user()->role === 'vendor') {
            $po->where('po_table.vendor_id', Auth::user()->vendor_id);
        }

        $po = $po->get();

        return view('po.index', compact('po', 
        'bulan', 'bulanList', 'tahunList','tahun'));
    }
}
