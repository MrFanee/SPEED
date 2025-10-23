<?php

namespace App\Http\Controllers;

use App\Vendor;
use Illuminate\Http\Request;

class VendorController extends Controller
{
    public function index()
    {
        $vendors = Vendor::all();
        return view('vendor.index', compact('vendor'));
    }

    public function create()
    {
        return view('vendor.create');
    }

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

    public function edit(Vendor $vendor)
    {
        return view('vendor.edit', compact('vendor'));
    }

    public function update(Request $request, Vendor $vendor)
    {
        $request->validate([
            'nickname' => 'required',
            'vendor_name' => 'required',
            'alamat' => 'required',
        ]);

        $vendor->update($request->only(['nickname', 'vendor_name', 'alamat']));
        return redirect()->route('vendor.index')->with('success', 'Vendor berhasil diupdate!');
    }

    public function destroy(Vendor $vendor)
    {
        $vendor->delete();
        return redirect()->route('vendor.index')->with('success', 'Vendor berhasil dihapus!');
    }
}
