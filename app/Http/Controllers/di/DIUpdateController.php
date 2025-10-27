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
            'qty_plan' => 'required',
            'qty_delivery' => 'required',
            'balance' => 'required',
        ]);

        $di = DI::findOrFail($id);
        $di->update($request->only(['qty_plan']));
        $di->update($request->only(['qty_delivery']));
        $di->update($request->only(['balance']));


        return redirect()->route('di.index')->with('success', 'DI berhasil diupdate!');
    }
}
