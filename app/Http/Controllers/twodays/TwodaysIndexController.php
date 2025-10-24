<?php

namespace App\Http\Controllers\twodays;

use App\Http\Controllers\Controller;
use App\Twodays;

class TwodaysIndexController extends Controller
{
    public function index()
    {
        $twodays = Twodays::with('part')->get();
        return view('twodays.index', compact('twodays'));
    }
}