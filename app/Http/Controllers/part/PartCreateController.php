<?php

namespace App\Http\Controllers\part;

use App\Http\Controllers\Controller;

class PartCreateController extends Controller
{
    public function create()
    {
        return view('part.create');
    }
}