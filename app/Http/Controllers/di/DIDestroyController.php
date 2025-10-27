<?php

namespace App\Http\Controllers\di;

use App\Http\Controllers\Controller;
use App\DI;

class DIDestroyController extends Controller
{
    public function destroy($id)
    {
        $di = DI::findOrFail($id);
        $di->delete();

        return redirect()->route('di.index')->with('success', 'DI berhasil dihapus!');
    }
}