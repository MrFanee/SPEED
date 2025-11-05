<?php

namespace App\Http\Controllers\stock;

use App\Stock;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class StockUpdateController extends Controller
{
    public function update(Request $request, $id)
    {
        $stock = Stock::findOrFail($id);
        $field = $request->input('field');
        $value = $request->input('value');

        $allowed = ['rm', 'wip', 'fg', 'kategori_problem', 'detail_problem'];
        if (!in_array($field, $allowed)) {
            return response()->json(['success' => false, 'message' => 'Invalid field']);
        }

        // Update field yang diedit
        $stock->$field = $value;

        // Hitung ulang judgement kalau FG berubah
        if ($field === 'fg') {
            $qty_po = $stock->qty_po ?? 0;
            $fg = (float)$stock->fg;
            $std = (float)$stock->std_stock;

            if ($qty_po > 0 && $fg >= $std) {
                $stock->judgement = 'OK';
            } elseif ($qty_po > 0 && $fg < $std) {
                $stock->judgement = 'NG';
            } elseif ($qty_po <= 0) {
                $stock->judgement = 'NO PO';
            } else {
                $stock->judgement = '-';
            }
        }

        // Validasi untuk NG
        if ($stock->judgement === 'NG') {
            if (empty($stock->kategori_problem) || empty($stock->detail_problem)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Wajib isi kategori dan detail problem untuk judgement NG'
                ]);
            }
        }

        $stock->save();

        return response()->json([
            'success' => true,
            'judgement' => $stock->judgement
        ]);
    }
}
