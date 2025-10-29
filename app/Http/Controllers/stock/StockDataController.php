<?php

namespace App\Http\Controllers\stock;

use App\Http\Controllers\Controller;
use App\Stock;


class StockDataController extends Controller
{
    public function index()
    {
        $stocks = Stock::with([
            'vendor',
            'part.po',
            'part.di',
        ])->get();

        // auto judgement sebelum dikirim ke view
        foreach ($stocks as $stock) {
            if ($stock->part && $stock->part->std_stock !== null) {
                $stock->judgement = ($stock->fg >= $stock->part->std_stock) ? 'OK' : 'NG';
            } else {
                $stock->judgement = '-';
            }
        }

        return response()->json($stocks);
    }
}
