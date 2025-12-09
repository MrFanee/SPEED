<?php

namespace App\Http\Controllers\stock;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StockUpdateController extends Controller
{
    public function update(Request $request, $id)
    {
        try {
            $stock = DB::table('master_stock')
                ->leftJoin('parts', 'master_stock.part_id', '=', 'parts.id')
                ->leftJoin('master_2hk', 'parts.id', '=', 'master_2hk.part_id')
                ->leftJoin(
                    DB::raw('(SELECT part_id, SUM(qty_po) AS qty_po 
                              FROM po_table 
                              GROUP BY part_id) AS po_sum'),
                    'parts.id',
                    '=',
                    'po_sum.part_id'
                )
                ->where('master_stock.id', $id)
                ->select(
                    'master_stock.*',
                    'master_2hk.std_stock',
                    'po_sum.qty_po'
                )
                ->first();

            if (!$stock) {
                return response()->json(['success' => false, 'message' => 'Data not found']);
            }

            $field = $request->input('field');
            $value = $request->input('value');

            // Update field
            if ($field && in_array($field, ['rm', 'wip', 'fg', 'kategori_problem', 'detail_problem'])) {
                DB::table('master_stock')
                    ->where('id', $id)
                    ->update([
                        $field => $value,
                        'updated_at' => now(),
                        'vendor_updated_at' => now()
                    ]);
            }

            // Hitung judgement 
            if (in_array($field, ['rm', 'wip', 'fg', 'kategori_problem', 'detail_problem'])) {

                $updatedStock = DB::table('master_stock')
                    ->leftJoin('parts', 'master_stock.part_id', '=', 'parts.id')
                    ->leftJoin('master_2hk', 'parts.id', '=', 'master_2hk.part_id')
                    ->leftJoin(
                        DB::raw('(SELECT part_id, SUM(qty_po) AS qty_po 
                                  FROM po_table 
                                  GROUP BY part_id) AS po_sum'),
                        'parts.id',
                        '=',
                        'po_sum.part_id'
                    )
                    ->where('master_stock.id', $id)
                    ->select(
                        'master_stock.*',
                        'master_2hk.std_stock',
                        'po_sum.qty_po'
                    )
                    ->first();

                $qty_po = (float)($updatedStock->qty_po ?? 0);
                $fg     = (float)($updatedStock->fg ?? 0);
                $std    = (float)($updatedStock->std_stock ?? 0);

                // RULE judgement
                if ($qty_po == 0) {
                    $judgement = 'NO PO';
                } elseif ($qty_po > 0 && $fg >= $std) {
                    $judgement = 'OK';
                } else {
                    $judgement = 'NG';
                }

                // Update judgement
                DB::table('master_stock')
                    ->where('id', $id)
                    ->update([
                        'judgement' => $judgement,
                        'updated_at' => now()
                    ]);
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
