<?php

namespace App\Http\Controllers\po;

use App\Http\Controllers\Controller;
use App\PO;
use Illuminate\Support\Facades\Auth;

class POIndexController extends Controller
{
    public function index()
    {
        $po = PO::with('vendor', 'part');

        if (Auth::user()->role === 'vendor') {
            $po->where('po_table.vendor_id', Auth::user()->vendor_id);
        }

        $po = $po->get();

        return view('po.index', compact('po'));
    }
}
