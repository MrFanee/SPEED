<?php

namespace App\Http\Controllers\di;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\DI;
use App\PO;

class DIUploadController extends Controller
{
    public function form()
    {
        return view('di.upload');
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
            if (count($row) !== 6) {
                return back()->with('error', "Jumlah kolom tidak sesuai!");
            }

            $rawDate = trim($row[0]);
            $item_code = trim($row[1]);
            $part_name = trim($row[2]);
            $po_number = trim($row[3]);
            $qty_plan = trim($row[4]);
            $qty_delivery = trim($row[5]);

            $po = PO::where('po_number', $po_number)->first();

            $delivery_date = null;
            if ($rawDate && preg_match('/\d{2}\/\d{2}\/\d{4}/', $rawDate)) {
                try {
                    $delivery_date = \Carbon\Carbon::createFromFormat('d/m/Y', $rawDate)->format('Y/m/d');
                } catch (\Exception $e) {
                    $delivery_date = null;
                }
            }

            if ($po) {
                DI::updateOrCreate(
                    [
                        'po_id' => $po->id,
                        'item_code' => $item_code,
                    ],
                    [
                        'delivery_date' => $delivery_date,
                        'part_name' => $part_name,
                        'qty_plan' => $qty_plan,
                        'qty_delivery' => $qty_delivery
                    ]
                );
                $imported++;
            } else {
                $skipped++;
            }
        }

        return redirect()
            ->route('di.index')
            ->with('success', "Upload selesai. $imported data berhasil diimpor, $skipped dilewati.");
    }
}
