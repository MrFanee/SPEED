<?php

namespace App\Http\Controllers\di;

use App\Http\Controllers\Controller;
use App\DI;

class DIIndexController extends Controller
{
    public function index()
    {
        $di = DI::with('po')->get();
        return view('di.index', compact('di'));
    }
}