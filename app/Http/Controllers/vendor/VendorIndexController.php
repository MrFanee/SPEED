<?php

namespace App\Http\Controllers\vendor;

use App\Http\Controllers\Controller;
use App\Vendor;

class VendorIndexController extends Controller
{
    public function index()
    {
        $query = request()->query('query'); 

        $vendors = Vendor::when($query, function ($q) use ($query) {
            $q->where('vendor_name', 'like', "%$query%")
                ->orWhere('nickname', 'like', "%$query%")
                ->orWhere('alamat', 'like', "%$query%");
        })->get();

        return view('vendor.index', compact('vendors', 'query'));
    }
}
