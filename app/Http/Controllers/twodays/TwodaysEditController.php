<?php

namespace App\Http\Controllers\twodays;

use App\Http\Controllers\Controller;
use App\Twodays;

class TwodaysEditController extends Controller
{
    public function edit($id)
    {
        $twodays = Twodays::findOrFail($id);
        return view('twodays.edit', compact('twodays'));
    }
}