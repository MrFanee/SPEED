<?php

namespace App\Http\Controllers\part;

use App\Http\Controllers\Controller;
use App\Part;

class PartEditController extends Controller
{
    public function edit($id)
    {
        $parts = Part::findOrFail($id);
        return view('part.edit', compact('parts'));
    }
}