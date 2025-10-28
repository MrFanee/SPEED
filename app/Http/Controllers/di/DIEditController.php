<?php

namespace App\Http\Controllers\di;

use App\Http\Controllers\Controller;
use App\DI;
use App\PO;

class DIEditController extends Controller
{
    public function edit($id)
    {
        $di = DI::findOrFail($id);
        $poList = PO::all();

        return view('di.edit', [
            'di' => $di,
            'poList' => $poList,
        ]);
    }
}
