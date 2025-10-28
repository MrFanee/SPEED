<?php

namespace App\Http\Controllers\po;

use App\Part;
use App\Vendor;
use App\Http\Controllers\Controller;

class POCreateController extends Controller
{
    public function create()
    {
        $parts = Part::all(); 
        $vendors = Vendor::all();
        return view('po.create', compact('parts', 'vendors'));
    }
}
