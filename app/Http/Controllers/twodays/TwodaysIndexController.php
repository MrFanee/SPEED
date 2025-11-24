<?php

namespace App\Http\Controllers\twodays;

use App\Http\Controllers\Controller;
use App\Twodays;

class TwodaysIndexController extends Controller
{
    public function index()
    {
        $query = request()->query('query'); 

        $twodays = Twodays::with('part')
            ->when($query, function ($q) use ($query) {
                $q->whereHas('part', function ($p) use ($query) {
                    $p->where('item_code', 'like', "%$query%")
                        ->orWhere('part_name', 'like', "%$query%");
                })
                    ->orWhere('std_stock', 'like', "%$query%");
            })
            ->get();

        return view('twodays.index', compact('twodays', 'query'));
    }
}
