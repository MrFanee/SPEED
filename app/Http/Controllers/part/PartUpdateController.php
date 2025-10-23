<?php

namespace App\Http\Controllers\part;

use App\Http\Controllers\Controller;
use App\Part;
use Illuminate\Http\Request;  

class PartUpdateController extends Controller
{
    public function update(Request $request, $id)
    {
        $request->validate([
            'part_name' => 'required',
            'part_number' => 'required',
            'item_code' => 'required',
        ]);

        $parts = Part::findOrFail($id);
        $parts->update($request->only(['part_name', 'part_number', 'item_code']));

        return redirect()->route('part.index')->with('success', 'Part berhasil diupdate!');
    }
}