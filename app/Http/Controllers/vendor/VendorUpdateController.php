<?php

namespace App\Http\Controllers\vendor;

use App\Http\Controllers\Controller;
use App\Vendor;
use Illuminate\Http\Request;  

class VendorUpdateController extends Controller
{
    public function update(Request $request, $id)
    {
        $request->validate([
            'nickname' => 'required',
            'vendor_name' => 'required',
            'alamat' => 'required',
        ]);

        $vendor = Vendor::findOrFail($id);
        $vendor->update($request->only(['nickname', 'vendor_name', 'alamat']));

        return redirect()->route('vendor.index')->with('success', 'Vendor berhasil diupdate!');
    }
}