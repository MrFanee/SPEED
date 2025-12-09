<?php

namespace App\Http\Controllers\di;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\DI;
use App\PO;
use App\Part;
use App\UploadFailure;
use Illuminate\Support\Facades\Auth;

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
        $failures = [];

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
            $part = Part::where('item_code', $item_code)->first();

            $delivery_date = null;

            $delivery_date = null;

            if ($rawDate && trim($rawDate) !== '') {
                $clean = trim($rawDate);

                try {
                    $date = \Carbon\Carbon::createFromFormat('d/m/Y', $clean);
                    $delivery_date = $date ? $date->format('Y-m-d') : null;
                } catch (\Exception $e) {
                    try {
                        $date = \Carbon\Carbon::parse($clean);
                        $delivery_date = $date->format('Y-m-d');
                    } catch (\Exception $e2) {
                        $delivery_date = null;
                    }
                }
            }

            if ($po && $part) {
                DI::updateOrCreate(
                    [
                        'po_id' => $po->id,
                        'part_id' => $part->id,
                        'delivery_date' => $delivery_date,
                    ],
                    [
                        'part_name' => $part_name,
                        'qty_plan' => $qty_plan,
                        'qty_delivery' => $qty_delivery
                    ]
                );
                $imported++;
            } else {
                $failures[] = [
                    'raw_date' => $rawDate,
                    'item_code' => $item_code,
                    'part_name' => $part_name,
                    'po_number' => $po_number,
                    'qty_plan' => $qty_plan,
                    'qty_delivery' => $qty_delivery,
                    'error_message' => $po ? 'Part tidak ditemukan'
                        : ($part ? 'PO tidak ditemukan'
                            : 'PO & Part tidak ditemukan'),
                ];
                $skipped++;
            }
        }

        if (!empty($failures)) {
            UploadFailure::create([
                'module' => 'master_di',
                'raw_data' => json_encode($failures),
                'error_message' => "$skipped record gagal",
                'uploaded_by' => Auth::id(),
            ]);
        }

        return redirect()
            ->route('di.index')
            ->with('success', "Upload selesai. $imported data berhasil diimpor, $skipped dilewati.");
    }
}
