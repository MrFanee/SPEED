<?php

namespace App\Http\Controllers\di;

use App\Http\Controllers\Controller;
use App\DI;

class DIEditController extends Controller
{
    public function edit($id)
    {
        $di = DI::findOrFail($id);
        return view('di.edit', compact('di'));
    }
}