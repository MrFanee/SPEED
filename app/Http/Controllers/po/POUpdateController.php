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
            'po_number' => 'required',
            'qty_po' => 'required',
            'qty_outstanding' => 'required',
            'status' => 'required',            
            'part_id' => 'nullable|exists:parts,id',
            'vendor_id' => 'nullable|exists:vendors,id',
        ]);

        $po = PO::findOrFail($id);

        $po->update($request->only(['po_id' ,'po_number', 'qty_po', 
        'qty_outstanding', 'status', 'part_id', 'vendor_id']));

        return redirect()->route('po.index')->with('success', 'PO berhasil diupdate!');
    }
}
