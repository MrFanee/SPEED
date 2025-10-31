<?php

namespace App\Http\Controllers\stock;

use App\Http\Controllers\Controller;
use App\Stock;

class StockIndexController extends Controller
{
    public function index()
    {
        $stock = Stock::with('vendor', 'part')->get();
        return view('stock.index', compact('stock'));
    }
}
