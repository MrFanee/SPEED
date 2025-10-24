<?php

namespace App\Http\Controllers\twodays;

use App\Part;
use App\Http\Controllers\Controller;

class TwodaysCreateController extends Controller
{
    public function create()
    {
        $parts = Part::all(); 
        return view('twodays.create', compact('parts'));
    }
}
