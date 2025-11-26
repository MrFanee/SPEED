<?php

namespace App\Http\Controllers\vendor;

use App\Http\Controllers\Controller;
use App\Vendor;
use Illuminate\Http\Request;

class VendorStoreController extends Controller
{
    public function store(Request $request)
    {
        $rules = [
            'kode_vendor' => 'required|unique:vendors,kode_vendor',
            'nickname' => 'required|max:6',
            'vendor_name' => 'required',
            'alamat' => 'required'
        ];

        $messages = [
            'kode_vendor.required' => 'Kode vendor wajib diisi!',
            'kode_vendor.unique' => 'Kode vendor sudah terdaftar!',
            'nickname.required' => 'Nickname wajib diisi!',
            'nickname.max' => 'Nickname maksimal 6 karakter!',
            'vendor_name.required' => 'Nama vendor wajib diisi!',
            'alamat.required' => 'Alamat wajib diisi!',
        ];

        $request->validate($rules, $messages);

        Vendor::create($request->only(['kode_vendor', 'nickname', 'vendor_name', 'alamat']));
        return redirect()->route('vendor.index')->with('success', 'Vendor berhasil ditambahkan!');
    }
}