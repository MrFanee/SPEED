<?php

namespace App\Http\Controllers\vendor;

use App\Http\Controllers\Controller;
use App\Vendor;
use Illuminate\Http\Request;

class VendorStoreController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'nickname' => 'required',
            'vendor_name' => 'required',
            'alamat' => 'required',
        ]);

        Vendor::create($request->only(['nickname', 'vendor_name', 'alamat']));
        return redirect()->route('vendor.index')->with('success', 'Vendor berhasil ditambahkan!');
    }
}