<?php

namespace App\Http\Controllers\po;

use App\Http\Controllers\Controller;
use App\PO;
use Illuminate\Http\Request;

class POUpdateController extends Controller
{
    public function update(Request $request, $id)
    {
        $request->validate([
            'period' => 'required',
            'po_number' => 'required',
            'purchase_group' => 'required',
            'part_id' => 'nullable|exists:parts,id',
            'vendor_id' => 'nullable|exists:vendors,id',
            'qty_po' => 'required',
            'qty_outstanding' => 'required',
            'delivery_date' => 'required',
            'status' => 'required',            
        ]);

        $po = PO::findOrFail($id);

        $po->update($request->only(['po_id', 'period','po_number', 'purchase_group', 
        'part_id', 'vendor_id', 'qty_po', 'qty_outstanding', 'delivery_date', 'status']));

        return redirect()->route('po.index')->with('success', 'PO berhasil diupdate!');
    }
}
