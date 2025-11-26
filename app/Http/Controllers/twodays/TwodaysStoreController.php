<?php

namespace App\Http\Controllers\twodays;

use App\Http\Controllers\Controller;
use App\Twodays;
use Illuminate\Http\Request;

class TwodaysStoreController extends Controller
{
    public function store(Request $request)
    {
        $rules = [
            'part_id' => 'required|exists:parts,id',
            'std_stock' => 'required|numeric|min:0'
        ];

        $messages = [
            'part_id.required' => 'Part harus dipilih!',
            'part_id.exists' => 'Part tidak valid atau tidak ditemukan!',

            'std_stock.required' => 'Standar stok wajib diisi!',
            'std_stock.numeric' => 'Standar stok harus berupa angka!',
            'std_stock.min' => 'Standar stok tidak boleh minus!',
        ];

        $request->validate($rules, $messages);

        Twodays::create([
            'part_id' => $request->part_id,
            'std_stock' => $request->std_stock,
        ]);

        return redirect()->route('twodays.index')->with('success', 'Standar stok berhasil ditambahkan!');
    }
}
