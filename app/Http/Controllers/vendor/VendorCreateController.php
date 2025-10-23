<?php

namespace App\Http\Controllers\vendor;

use App\Http\Controllers\Controller;

class VendorCreateController extends Controller
{
    public function create()
    {
        return view('vendor.create');
    }
}