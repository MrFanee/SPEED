<?php

namespace App\Http\Controllers\part;

use App\Http\Controllers\Controller;
use App\Part;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
        // DB::table('master_2hk')->insert([
        //     'part_id' => $part->id,
        //     'std_stock' => 0,
        //     'created_at' => now(),
        //     'updated_at' => now()
        // ]);

        return redirect()->route('part.index')->with('success', 'Part berhasil ditambahkan!');
    }
}
