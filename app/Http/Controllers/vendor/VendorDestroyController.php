<?php

namespace App\Http\Controllers\vendor;

use App\Http\Controllers\Controller;
use App\Vendor;
use Illuminate\Http\Request;

class VendorDestroyController extends Controller
{
    public function destroy($id)
    {
        $vendors = Vendor::findOrFail($id);
        $vendors->delete();

        return redirect()->route('vendor.index')->with('success', 'Vendor berhasil dihapus!');
    }
}