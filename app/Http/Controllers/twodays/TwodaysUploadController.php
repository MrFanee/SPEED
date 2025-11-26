<?php

namespace App\Http\Controllers\twodays;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Twodays;
use App\Part;

class TwodaysUploadController extends Controller
{
    public function form()
    {
        return view('twodays.upload');
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
            if (count($row) !== 3) {
                return back()->with('error', "Jumlah kolom tidak sesuai!");
            }

            $item_code = trim($row[0]);
            $part_name = trim($row[1]);
            $std_stock = trim($row[2]);

            $parts = Part::where('item_code', $item_code)->first();

            if ($parts) {
                Twodays::updateOrCreate(
                    ['part_id' => $parts->id],
                    [
                        'part_name' => $part_name,
                        'std_stock' => $std_stock
                    ]
                );
                $imported++;
            } else {
                $skipped++;
            }
        }

        return redirect()
            ->route('twodays.index')
            ->with('success', "Upload selesai. $imported data berhasil diimpor, $skipped dilewati.");
    }
}
