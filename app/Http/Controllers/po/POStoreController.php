<?php

namespace App\Http\Controllers\po;

use App\Http\Controllers\Controller;
use App\PO;
use Illuminate\Http\Request;

class POStoreController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'po_number' => 'required',
            'qty_po' => 'required',
            'qty_outstanding' => 'required',
            'status' => 'required',            
            'part_id' => 'nullable|exists:parts,id',
            'vendor_id' => 'nullable|exists:vendors,id',
        ]);

        PO::create([
            'po_number' => $request->po_number,
            'qty_po' => $request->qty_po,
            'qty_outstanding' => $request->qty_outstanding,
            'status' => $request->status,
            'part_id' => $request->part_id,
            'vendor_id' => $request->vendor_id,
        ]);

        return redirect()->route('po.index')->with('success', 'PO berhasil ditambahkan!');
    }
}
