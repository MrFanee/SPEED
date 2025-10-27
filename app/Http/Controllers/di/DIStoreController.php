<?php

namespace App\Http\Controllers\di;

use App\Http\Controllers\Controller;
use App\DI;
use Illuminate\Http\Request;

class DIStoreController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'po_id' => 'required|exists:po_table,id',
            'qty_plan' => 'required',
            'qty_delivery' => 'required',
            'balance' => 'required',
        ]);

        DI::create([
            'po_id' => $request->po_id,
            'qty_plan' => $request->qty_plan,
            'qty_delivery' => $request->qty_delivery,
            'balance' => $request->balance,
        ]);

        return redirect()->route('di.index')->with('success', 'DI berhasil ditambahkan!');
    }
}
