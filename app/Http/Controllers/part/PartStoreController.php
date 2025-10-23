<?php

namespace App\Http\Controllers\part;

use App\Http\Controllers\Controller;
use App\Part;
use Illuminate\Http\Request;

class PartStoreController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'part_name' => 'required',
            'part_number' => 'required',
            'item_code' => 'required',
        ]);

        Part::create($request->only(['part_name', 'part_number', 'item_code']));
        return redirect()->route('part.index')->with('success', 'Part berhasil ditambahkan!');
    }
}