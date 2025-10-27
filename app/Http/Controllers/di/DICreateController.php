<?php

namespace App\Http\Controllers\di;

use App\PO;
use App\Http\Controllers\Controller;

class DICreateController extends Controller
{
    public function create()
    {
        $po = PO::all(); 
        return view('di.create', compact('po'));
    }
}
