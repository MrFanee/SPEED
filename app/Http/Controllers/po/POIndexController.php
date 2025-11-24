<?php

namespace App\Http\Controllers\po;

use App\Http\Controllers\Controller;
use App\PO;

class POIndexController extends Controller
{
    public function index()
    {
        $po = PO::with('vendor', 'part')->get();

        return view('po.index', compact('po'));
    }
}
