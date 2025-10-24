<?php

namespace App\Http\Controllers\part;

use App\Http\Controllers\Controller;
use App\Part;

class PartDestroyController extends Controller
{
    public function destroy($id)
    {
        $parts = Part::findOrFail($id);
        $parts->delete();

        return redirect()->route('part.index')->with('success', 'Vendor berhasil dihapus!');
    }
}