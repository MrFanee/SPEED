<?php

namespace App\Http\Controllers\stock;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Stock;
use App\Part;

class StockUploadController extends Controller
{
    public function form()
    {
        return view('stock.upload');
    }

    public function upload(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:csv,txt|max:2048',
        ]);

        $file = $request->file('file');
        $path = $file->getRealPath();
        $rows = array_map('str_getcsv', file($path));

        if (count($rows) < 2) {
            return back()->with('error', 'File CSV kosong atau tidak valid.');
        }

        // $header = array_map('strtolower', $rows[0]);
        // $expected = ['item_code', 'std_stock'];

        // if ($header !== $expected) {
        //     return back()->with('error', 'Format header CSV harus: item_code,std_stock');
        // }

        $imported = 0;
        $skipped = 0;

        foreach (array_slice($rows, 1) as $row) {
            if (count($row) < 2) continue;

            $item_code = trim($row[0]);
            $part_name = trim($row[1]);
            $rm = trim($row[2]);
            $wip = trim($row[3]);
            $fg = trim($row[4]);
            $kategori_problem = trim($row[5]);
            $detail_problem = trim($row[5]);

            $part = Part::where('item_code', $item_code)->first();
            $part = Part::where('part_name', $part_name)->first();

            if ($part) {
                Stock::updateOrCreate(
                    [
                        'part_id' => $part->id
                    ],
                    [
                        'rm' => $rm,
                        'wip' => $wip,
                        'fg' => $fg,
                        'kategori_problem' => $kategori_problem,
                        'detail_problem' => $detail_problem
                    ]
                );
                $imported++;
            } else {
                $skipped++;
            }
        }

        return redirect()
            ->route('po.index')
            ->with('success', "Upload selesai. $imported data berhasil diimpor, $skipped dilewati.");
    }
}
