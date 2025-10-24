<?php

namespace App\Http\Controllers\twodays;

use App\Http\Controllers\Controller;
use App\Twodays;
use Illuminate\Http\Request;

class TwodaysStoreController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'part_id' => 'required|exists:part,id',
            'std_stock' => 'required',
        ]);

        Twodays::create([
            'part_id' => $request->part_id,
            'std_stock' => $request->std_stock,
        ]);

        return redirect()->route('twodays.index')->with('success', 'Standar stok berhasil ditambahkan!');
    }
}
