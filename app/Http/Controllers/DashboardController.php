<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $dashboard_data = [
            'total_item' => 1245,
            'item_ng' => 87,
            'item_ok' => 1158,
            'item_no_po' => 42,
            'material' => 632,
            'machine' => 245,
            'man' => 158,
            'method' => 210
        ];

        $chartTitles = [
            ["vendorname" => "ATMI", "NG" => 5, "OK" => 15],
            ["vendorname" => "DAE BAEK", "NG" => 3, "OK" => 20],
            ["vendorname" => "DRC", "NG" => 7, "OK" => 12],
            ["vendorname" => "KAINDO", "NG" => 2, "OK" => 18],
            ["vendorname" => "KOSEN", "NG" => 1, "OK" => 25],
            ["vendorname" => "LESTARI", "NG" => 4, "OK" => 21],
            ["vendorname" => "PHM", "NG" => 5, "OK" => 15],
            ["vendorname" => "RJM", "NG" => 3, "OK" => 20],
            ["vendorname" => "SJT", "NG" => 7, "OK" => 12],
            ["vendorname" => "SLAI", "NG" => 2, "OK" => 18],
            ["vendorname" => "SPI", "NG" => 1, "OK" => 25],
            ["vendorname" => "WHAIN", "NG" => 4, "OK" => 21]
        ];

        return view('dashboard.index', compact('dashboard_data', 'chartTitles'));
    }
}
