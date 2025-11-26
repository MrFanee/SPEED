<?php

namespace App\Http\Controllers\po;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\PO;
use App\Part;
use App\Vendor;

class POUploadController extends Controller
{
    public function form()
    {
        return view('po.upload');
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

            $period = trim($row[0]);
            $po_number = trim($row[1]);
            $purchase_group = trim($row[2]);
            $kode_vendor = trim($row[3]);
            $item_code = trim($row[4]);
            $qty_po = trim($row[5]);
            $qty_outstanding = trim($row[6]);
            $delivery_date = trim($row[7]);

            $part = Part::where('item_code', $item_code)->first();
            $vendor = Vendor::where('kode_vendor', $kode_vendor)->first();

            if ($part && $vendor) {
                PO::updateOrCreate(
                    [
                        'po_number' => $po_number
                    ],
                    [
                        'period' => $period,
                        'po_number' => $po_number,
                        'purchase_group' => $purchase_group,
                        'vendor_id' => $vendor->id,
                        'part_id' => $part->id,
                        'qty_po' => $qty_po,
                        'qty_outstanding' => $qty_outstanding,
                        'delivery_date' => $delivery_date
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
