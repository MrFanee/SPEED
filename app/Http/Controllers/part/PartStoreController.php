<?php

namespace App\Http\Controllers\part;

use App\Http\Controllers\Controller;
use App\Part;
use Illuminate\Http\Request;

class PartStoreController extends Controller
{
    public function store(Request $request)
    {
        $rules = [
            'part_name' => 'required',
            'part_number' => 'required',
            'item_code' => 'required'
        ];

        $messages = [
            'part_name.required' => 'Part Name wajib diisi!',
            'item_code.required' => 'Item Code wajib diisi!',
        ];

        $request->validate($rules, $messages);

        Part::create($request->only(['part_name', 'part_number', 'item_code']));
        return redirect()->route('part.index')->with('success', 'Part berhasil ditambahkan!');
    }
}