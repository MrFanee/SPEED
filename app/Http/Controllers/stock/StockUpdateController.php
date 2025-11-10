<?php

namespace App\Http\Controllers\stock;

use App\Stock;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StockUpdateController extends Controller
{
    public function update(Request $request, $id)
    {
        try {
            // Ambil data sebelum update
            $stock = DB::table('master_stock')
                ->leftJoin('parts', 'master_stock.part_id', '=', 'parts.id')
                ->leftJoin('master_2hk', 'parts.id', '=', 'master_2hk.part_id')
                ->leftJoin('po_table', 'parts.id', '=', 'po_table.part_id')
                ->where('master_stock.id', $id)
                ->select(
                    'master_stock.*',
                    'master_2hk.std_stock',
                    'po_table.qty_po'
                )
                ->first();

            if (!$stock) {
                return response()->json(['success' => false, 'message' => 'Data not found']);
            }

            $field = $request->input('field');
            $value = $request->input('value');

            //  UPDATE FIELD
            if ($field && in_array($field, ['rm', 'wip', 'fg', 'kategori_problem', 'detail_problem'])) {
                DB::table('master_stock')
                    ->where('id', $id)
                    ->update([$field => $value]);
            }

            // HITUNG JUDGEMENT 
            if (in_array($field, ['rm', 'wip', 'fg'])) {
                $updatedStock = DB::table('master_stock')
                    ->leftJoin('parts', 'master_stock.part_id', '=', 'parts.id')
                    ->leftJoin('master_2hk', 'parts.id', '=', 'master_2hk.part_id')
                    ->leftJoin('po_table', 'parts.id', '=', 'po_table.part_id')
                    ->where('master_stock.id', $id)
                    ->select(
                        'master_stock.*',
                        'master_2hk.std_stock',
                        'po_table.qty_po'
                    )
                    ->first();

                $qty_po = (float)($updatedStock->qty_po ?? 0);
                $fg = (float)($updatedStock->fg ?? 0);
                $std = (float)($updatedStock->std_stock ?? 0);

                if ($qty_po > 0 && $fg >= $std) {
                    $judgement = 'OK';
                } elseif ($qty_po > 0 && $fg < $std) {
                    $judgement = 'NG';
                } elseif ($qty_po <= 0) {
                    $judgement = 'NO PO';
                } else {
                    $judgement = $stock->judgement ?? '-';
                }

                // Update judgement
                DB::table('master_stock')
                    ->where('id', $id)
                    ->update(['judgement' => $judgement]);
            } else {
                $judgement = $stock->judgement;
            }

            return response()->json([
                'success' => true,
                'judgement' => $judgement,
                'field_updated' => $field
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }
}
