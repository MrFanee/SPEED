<?php

namespace App\Http\Controllers\part;

use App\Http\Controllers\Controller;
use App\Part;
use Illuminate\Http\Request;

class PartDestroyController extends Controller
{
    public function destroy($id)
    {
        $parts = Part::findOrFail($id);
        $parts->delete();

        return redirect()->route('part.index')->with('success', 'Vendor berhasil dihapus!');
    }
}