<?php

namespace App\Http\Controllers\part;

use App\Http\Controllers\Controller;
use App\Part;

class PartDestroyController extends Controller
{
    public function destroy($id)
    {
        try {
            Part::where('id', $id)->delete();
            return back()->with('success', 'Part berhasil dihapus');
        } catch (\Exception $e) {
            return back()->with('error', 'Part tidak bisa dihapus karena masih dipakai di data 2 days stock atau standar 2HK');
        }
    }
}
