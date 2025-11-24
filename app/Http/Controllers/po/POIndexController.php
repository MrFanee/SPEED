<?php

namespace App\Http\Controllers\po;

use App\Http\Controllers\Controller;
use App\PO;
use Illuminate\Http\Request;

class POIndexController extends Controller
{
    public function index(Request $request)
    {
        $query = $request->input('query');

        $po = PO::with('vendor', 'part')
            ->when($query, function ($q) use ($query) {
                $q->whereHas('vendor', function ($v) use ($query) {
                    $v->where('vendor_name', 'like', '%' . $query . '%')
                        ->orWhere('nickname', 'like', '%' . $query . '%');
                })
                    ->orWhereHas('part', function ($p) use ($query) {
                        $p->where('item_code', 'like', '%' . $query . '%')
                            ->orWhere('part_name', 'like', '%' . $query . '%');
                    })
                    ->orWhere('po_number', 'like', '%' . $query . '%')
                    ->orWhere('qty_po', 'like', '%' . $query . '%')
                    ->orWhere('qty_outstanding', 'like', '%' . $query . '%');
            })
            ->get();

        return view('po.index', compact('po', 'query'));
    }
}
