<?php

namespace App\Http\Controllers\report;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class ReportYearlyController extends Controller
{
    public function index(Request $request)
    {
        $year = $request->get('year', now()->year);

        $data = DB::table('master_stock')
            ->select(
                'nickname',
                DB::raw('SUM(CASE WHEN judgement = "OK" THEN 1 ELSE 0 END) as ok_count'),
                DB::raw('SUM(CASE WHEN judgement = "NG" THEN 1 ELSE 0 END) as ng_count'),
                DB::raw('SUM(CASE WHEN judgement = "NO PO" THEN 1 ELSE 0 END) as nopo_count'),
                DB::raw('COUNT(*) as total')
            )
            ->whereYear('tanggal', $year)
            ->groupBy('nickname')
            ->orderBy('nickname')
            ->get();

        return view('report.yearly', compact('data', 'year'));
    }
}
