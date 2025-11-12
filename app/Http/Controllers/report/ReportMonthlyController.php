<?php

namespace App\Http\Controllers\report;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class ReportMonthlyController extends Controller
{
    public function index(Request $request)
    {
        $month = $request->get('month', now()->format('Y-m'));

        $data = DB::table('master_stock')
            ->select(
                'nickname',
                DB::raw('AVG(fg) as avg_fg'),
                DB::raw('AVG(std_stock) as avg_std'),
                DB::raw('SUM(CASE WHEN judgement = "NG" THEN 1 ELSE 0 END) as ng_count'),
                DB::raw('COUNT(*) as total')
            )
            ->whereRaw('DATE_FORMAT(tanggal, "%Y-%m") = ?', [$month])
            ->groupBy('nickname')
            ->orderBy('nickname')
            ->get();

        return view('report.monthly', compact('data', 'month'));
    }
}
