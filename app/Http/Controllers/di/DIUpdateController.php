<?php

namespace App\Http\Controllers\di;

use App\Http\Controllers\Controller;
use App\DI;
use Illuminate\Http\Request;

class DIUpdateController extends Controller
{
    public function update(Request $request, $id)
    {
        $rules = [
            'po_id' => 'required|exists:po_table,id',
            'qty_plan' => 'required|numeric|min:1',
            'qty_delivery' => 'required|numeric|min:0'
        ];

        $messages = [
            'po_id.required' => 'PO harus dipilih!',
            'po_id.exists' => 'PO tidak valid atau tidak ditemukan!',

            'qty_plan.required' => 'Qty Plan wajib diisi!',
            'qty_plan.numeric' => 'Qty Plan harus berupa angka!',
            'qty_plan.min' => 'Qty Plan harus lebih dari 0!',

            'qty_delivery.required' => 'Qty Delivery wajib diisi!',
            'qty_delivery.numeric' => 'Qty Delivery harus berupa angka!',
            'qty_delivery.min' => 'Qty Delivery tidak boleh minus!',
        ];

        $request->validate($rules, $messages);

        $di = DI::findOrFail($id);

        $di->update([
            'po_id' => $request->po_id,
            'qty_plan' => $request->qty_plan,
            'qty_delivery' => $request->qty_delivery,
        ]);

        return redirect()->route('di.index')->with('success', 'DI berhasil diupdate!');
    }
}
