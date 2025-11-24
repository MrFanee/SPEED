<?php

namespace App\Http\Controllers\part;

use App\Http\Controllers\Controller;
use App\Part;
use Illuminate\Http\Request;

class PartIndexController extends Controller
{
    public function index(Request $request)
    {
        $query = $request->input('query');

        $parts = Part::when($query, function ($q) use ($query) {
            $q->where('item_code', 'LIKE', "%$query%")
                ->orWhere('part_name', 'LIKE', "%$query%")
                ->orWhere('part_number', 'LIKE', "%$query%");
        })
            ->get();

        return view('part.index', compact('parts'));
    }
}
