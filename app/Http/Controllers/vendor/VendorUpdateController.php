<?php

namespace App\Http\Controllers\vendor;

use App\Http\Controllers\Controller;
use App\Vendor;
use Illuminate\Http\Request;  

class VendorUpdateController extends Controller
{
    public function update(Request $request, $id)
    {
        $rules = [
            'nickname' => 'required|max:6',
            'vendor_name' => 'required',
            'alamat' => 'required'
        ];

        $messages = [
            'nickname.required' => 'Nickname wajib diisi!',
            'nickname.max' => 'Nickname maksimal 6 karakter!',
            'vendor_name.required' => 'Nama vendor wajib diisi!',
            'alamat.required' => 'Alamat wajib diisi!',
        ];

        $request->validate($rules, $messages);

        $vendor = Vendor::findOrFail($id);
        $vendor->update($request->only(['nickname', 'vendor_name', 'alamat']));

        return redirect()->route('vendor.index')->with('success', 'Vendor berhasil diupdate!');
    }
}