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

            // ✅ UPDATE FIELD - ini yang utama
            if ($field && in_array($field, ['rm', 'wip', 'fg', 'kategori_problem', 'detail_problem'])) {
                DB::table('master_stock')
                    ->where('id', $id)
                    ->update([$field => $value]);
            }

            // ✅ JIKA field yang diupdate adalah kategori_problem atau detail_problem
            // DAN judgement saat ini adalah OK, maka langsung return success
            if (in_array($field, ['kategori_problem', 'detail_problem']) && $stock->judgement === 'OK') {
                return response()->json([
                    'success' => true,
                    'judgement' => 'OK', // Tetap OK
                    'message' => 'Field updated successfully'
                ]);
            }

            // ✅ HITUNG JUDGEMENT hanya jika field yang diupdate mempengaruhi perhitungan
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

                    // ✅ VALIDASI HANYA UNTUK NG: wajib isi kategori & detail problem
                    $currentKategori = $updatedStock->kategori_problem ?? '';
                    $currentDetail = $updatedStock->detail_problem ?? '';

                    if (empty($currentKategori) || empty($currentDetail)) {
                        // Kembalikan ke judgement sebelumnya
                        $judgement = $stock->judgement ?? 'OK';

                        // Update ke judgement sebelumnya
                        DB::table('master_stock')
                            ->where('id', $id)
                            ->update(['judgement' => $judgement]);

                        return response()->json([
                            'success' => false,
                            'message' => 'Harap isi Kategori Problem dan Detail Problem terlebih dahulu!',
                            'judgement' => $judgement,
                            'requires_problem_data' => true
                        ]);
                    }
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
                // Jika field yang diupdate tidak mempengaruhi judgement, pertahankan nilai lama
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
