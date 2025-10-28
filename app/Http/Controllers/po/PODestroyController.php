<?php

namespace App\Http\Controllers\po;

use App\Http\Controllers\Controller;
use App\PO;

class PODestroyController extends Controller
{
    public function destroy($id)
    {
        $po = PO::findOrFail($id);
        $po->delete();

        return redirect()->route('po.index')->with('success', 'PO berhasil dihapus!');
    }
}