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
            'kode_vendor' => 'required',
            'nickname' => 'required',
            'vendor_name' => 'required',
            'alamat' => 'required',
        ]);

        Vendor::create($request->only(['kode_vendor', 'nickname', 'vendor_name', 'alamat']));
        return redirect()->route('vendor.index')->with('success', 'Vendor berhasil ditambahkan!');
    }
}