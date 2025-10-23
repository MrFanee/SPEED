<?php

namespace App\Http\Controllers\vendor;

use App\Http\Controllers\Controller;
use App\Vendor;

class VendorEditController extends Controller
{
    public function edit($id)
    {
        $vendors = Vendor::findOrFail($id);
        return view('vendor.edit', compact('vendors'));
    }
}