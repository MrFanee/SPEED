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
            $std_stock = trim($row[1]);

            $parts = Part::where('item_code', $item_code)->first();

            if ($parts) {
                Twodays::updateOrCreate(
                    ['part_id' => $parts->id],
                    ['std_stock' => $std_stock]
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
