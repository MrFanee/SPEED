<?php

namespace App\Http\Controllers\twodays;

use App\Http\Controllers\Controller;
use App\Twodays;

class TwodaysDestroyController extends Controller
{
    public function destroy($id)
    {
        $twodays = Twodays::findOrFail($id);
        $twodays->delete();

        return redirect()->route('twodays.index')->with('success', 'Standar stok berhasil dihapus!');
    }
}