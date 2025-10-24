<?php

namespace App\Http\Controllers\twodays;

use App\Http\Controllers\Controller;
use App\Twodays;
use Illuminate\Http\Request;  

class TwodaysUpdateController extends Controller
{
    public function update(Request $request, $id)
    {
        $request->validate([
            'std_stock' => 'required',
        ]);

        $twodays = Twodays::findOrFail($id);
        $twodays->update($request->only(['std_stock']));

        return redirect()->route('twodays.index')->with('success', 'Standar stok berhasil diupdate!');
    }
}