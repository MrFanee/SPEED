<?php

namespace App\Http\Controllers\stock;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Stock;
use App\Part;
use App\Vendor;


class StockUploadController extends Controller
{
    public function form()
    {
        return view('stock.upload');
    }

    public function upload(Request $request)
    {
        $request->validate(
            [
                'file' => 'required|mimes:csv,txt|max:2048',
            ],
            [
                'file.required' => 'File belum dipilih!',
                'file.mimes' => 'Format file harus CSV!',
                'file.max' => 'Ukuran file maksimal 2MB!',
            ]
        );

        $file = $request->file('file');
        $path = $file->getRealPath();
        $rows = array_map('str_getcsv', file($path));

        if (count($rows) < 2) {
            return back()->with('error', 'File CSV kosong atau tidak valid!');
        }

        $imported = 0;
        $skipped = 0;

        foreach (array_slice($rows, 1) as $row) {
            if (count($row) !== 8) {
                return back()->with('error', "Jumlah kolom tidak sesuai!");
            }

            $item_code = trim($row[0]);
            $part_name = trim($row[1]);
            $rm  = is_numeric($row[2]) ? intval($row[2]) : 0;
            $wip = is_numeric($row[3]) ? intval($row[3]) : 0;
            $fg  = is_numeric($row[4]) ? intval($row[4]) : 0;
            $kategori_problem = trim($row[5]) ?: null;
            $detail_problem = trim($row[6]) ?: null;
            $kode_vendor = trim($row[7]);

            $part = Part::where('item_code', $item_code)->first();
            $vendor = Vendor::where('kode_vendor', $kode_vendor)->first();

            if ($part && $vendor) {
                Stock::updateOrCreate(
                    [
                        'part_id' => $part->id,
                        'vendor_id' => $vendor->id,
                        'tanggal' => now()->toDateString(),
                    ],
                    [
                        'item_code' => $item_code,
                        'part_name' => $part_name,
                        'rm' => $rm,
                        'wip' => $wip,
                        'fg' => $fg,
                        'kategori_problem' => $kategori_problem,
                        'detail_problem' => $detail_problem,
                        'kode_vendor' => $kode_vendor
                    ]
                );
                $imported++;
            } else {
                $skipped++;
            }
        }

        return redirect()
            ->route('stock.index')
            ->with('success', "Upload selesai. $imported data berhasil diimpor, $skipped dilewati.");
    }
}
