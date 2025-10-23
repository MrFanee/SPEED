<?php

namespace App\Http\Controllers\vendor;

use App\Http\Controllers\Controller;
use App\Vendor;

class VendorIndexController extends Controller
{
    public function index()
    {
        $vendors = Vendor::all();
        return view('vendor.index', compact('vendors'));
    }
}