<?php

namespace App\Http\Controllers\vendor;

use App\Http\Controllers\Controller;
use App\Vendor;

class VendorDestroyController extends Controller
{
    public function destroy($id)
    {
        try {
            Vendor::where('id', $id)->delete();
            return back()->with('success', 'Vendor berhasil dihapus');
        } catch (\Exception $e) {
            return back()->with('error', 'Vendor tidak bisa dihapus karena masih dipakai di data 2 days stock');
        }
    }
}
