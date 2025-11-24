<?php

namespace App\Http\Controllers\vendor;

use App\Http\Controllers\Controller;
use App\Vendor;

class VendorIndexController extends Controller
{
    public function index()
    {
        $query = request()->query('query'); 

        $vendors = Vendor::all();

        return view('vendor.index', compact('vendors', 'query'));
    }
}
