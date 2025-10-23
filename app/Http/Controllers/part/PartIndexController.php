<?php

namespace App\Http\Controllers\part;

use App\Http\Controllers\Controller;
use App\Part;

class PartIndexController extends Controller
{
    public function index()
    {
        $parts = Part::all();
        return view('part.index', compact('parts'));
    }
}