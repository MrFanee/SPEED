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
            'period' => 'required',
            'po_number' => 'required',
            'purchase_group' => 'required',
            'vendor_id' => 'nullable|exists:vendors,id',
            'part_id' => 'nullable|exists:parts,id',
            'qty_po' => 'required',
            'qty_outstanding' => 'required',
            'delivery_date' => 'required',
            'status' => 'required',            
        ]);

        PO::create([
            'period' => $request->period,
            'po_number' => $request->po_number,
            'purchase_group' => $request->purchase_group,
            'vendor_id' => $request->vendor_id,
            'part_id' => $request->part_id,
            'qty_po' => $request->qty_po,
            'qty_outstanding' => $request->qty_outstanding,
            'delivery_date' => $request->delivery_date,
            'status' => $request->status,
        ]);

        return redirect()->route('po.index')->with('success', 'PO berhasil ditambahkan!');
    }
}
