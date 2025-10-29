<?php

namespace App\Http\Controllers\stock;

use App\Http\Controllers\Controller;

class StockIndexController extends Controller
{
    public function index()
    {
        return view('stock.index');
    }
}
