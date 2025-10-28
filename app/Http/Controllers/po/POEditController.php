<?php

namespace App\Http\Controllers\po;

use App\Http\Controllers\Controller;
use App\PO;
use App\Part;
use App\Vendor;

class POEditController extends Controller
{
    public function edit($id)
    {
        $po = PO::findOrFail($id);
        $partList = Part::all();
        $vendorList = Vendor::all();

        return view('po.edit', [
            'po' => $po,
            'partList' => $partList,
            'vendorList' => $vendorList,
        ]);
    }
}
