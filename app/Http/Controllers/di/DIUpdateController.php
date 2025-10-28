<?php

namespace App\Http\Controllers\di;

use App\Http\Controllers\Controller;
use App\DI;
use Illuminate\Http\Request;

class DIUpdateController extends Controller
{
    public function update(Request $request, $id)
    {
        $request->validate([
            'po_id' => 'nullable|exists:po_table,id',
            'qty_plan' => 'required',
            'qty_delivery' => 'required',
        ]);

        $di = DI::findOrFail($id);

        $di->update($request->only(['po_id' ,'qty_plan', 'qty_delivery']));

        return redirect()->route('di.index')->with('success', 'DI berhasil diupdate!');
    }
}
