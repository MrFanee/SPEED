<?php

namespace App\Http\Controllers\report;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class ReportVendorController extends Controller
{
    public function index(Request $request)
    {
        $tanggal = $request->get('tanggal', now()->toDateString());

        $data = DB::table('master_stock')
            ->whereDate('tanggal', $tanggal)
            ->select('nickname', 'item_code', 'part_name', 'qty_po', 'fg', 'std_stock', 'judgement')
            ->orderBy('nickname')
            ->get();

        return view('report.vendor', compact('data', 'tanggal'));
    }
}
