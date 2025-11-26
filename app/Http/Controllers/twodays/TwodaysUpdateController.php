<?php

namespace App\Http\Controllers\twodays;

use App\Http\Controllers\Controller;
use App\Twodays;
use Illuminate\Http\Request;  

class TwodaysUpdateController extends Controller
{
    public function update(Request $request, $id)
    {
        $rules = [
            'std_stock' => 'required|numeric|min:0'
        ];

        $messages = [
            'std_stock.required' => 'Standar stok wajib diisi!',
            'std_stock.numeric' => 'Standar stok harus berupa angka!',
            'std_stock.min' => 'Standar stok tidak boleh minus!',
        ];

        $request->validate($rules, $messages);

        $twodays = Twodays::findOrFail($id);
        $twodays->update($request->only(['std_stock']));

        return redirect()->route('twodays.index')->with('success', 'Standar stok berhasil diupdate!');
    }
}