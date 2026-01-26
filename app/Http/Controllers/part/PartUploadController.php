<?php

namespace App\Http\Controllers\part;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Part;


class PartUploadController extends Controller
{
    public function form()
    {
        return view('part.upload');
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

        if (count($rows) <= 1) {
            return back()->with('error', 'File CSV kosong atau tidak valid!');
        }

        $imported = 0;
        $skipped = 0;

        foreach (array_slice($rows, 1) as $row) {
            if (count($row) !== 3) {
                return back()->with('error', "Jumlah kolom tidak sesuai!");
            }

            $item_code = trim($row[0]);
            $part_number = trim($row[1]);
            $part_name = trim($row[2]);


            $part = Part::where('item_code', $item_code)->first();

            Part::updateOrCreate(
                ['item_code' => $item_code],
                [
                    'part_name'   => $part_name,
                    'part_number' => $part_number
                ]
            );

            $imported++;
        }

        return redirect()
            ->route('part.index')
            ->with('success', "Upload selesai. $imported data berhasil diimpor.");
    }
}
