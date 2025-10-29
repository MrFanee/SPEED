<?php

namespace App\Http\Controllers\stock;

use App\Stock;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class StockUpdateController extends Controller
{
    public function update(Request $request, $id)
    {
        $stock = Stock::with('part')->findOrFail($id);

        // logic judgement
        $stdStock = $stock->part ? $stock->part->std_stock : 0;
        $judgement = ($request->fg >= $stdStock) ? 'OK' : 'NG';

        // kalau NG dan kategori_problem/detail_problem kosong → tidak boleh update
        if ($judgement === 'NG' && (empty($request->kategori_problem) || empty($request->detail_problem))) {
            return response()->json(['error' => 'Data tidak dapat diubah sebelum kategori & detail problem diisi.'], 422);
        }

        $stock->update([
            'rm' => $request->rm,
            'wip' => $request->wip,
            'fg' => $request->fg,
            'judgement' => $judgement,
            'kategori_problem' => $request->kategori_problem,
            'detail_problem' => $request->detail_problem,
        ]);

        return response()->json(['success' => 'Data berhasil diperbarui', 'judgement' => $judgement]);
    }
}
