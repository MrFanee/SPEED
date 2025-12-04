<?php

namespace App\Http\Controllers\po;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\PO;
use App\Part;
use App\Vendor;
use App\UploadFailure;
use Illuminate\Support\Facades\Auth;

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
        $failures = [];

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
            $rawDate = trim($row[7]);

            $part = Part::where('item_code', $item_code)->first();
            $vendor = Vendor::where('kode_vendor', $kode_vendor)->first();

            $delivery_date = null;
            if ($rawDate && preg_match('/\d{2}\/\d{2}\/\d{4}/', $rawDate)) {
                try {
                    $delivery_date = \Carbon\Carbon::createFromFormat('d/m/Y', $rawDate)->format('Y/m/d');
                } catch (\Exception $e) {
                    $delivery_date = null;
                }
            }

            if ($part && $vendor) {
                PO::updateOrCreate(
                    [
                        'po_number' => $po_number,
                        'vendor_id' => $vendor->id,
                        'part_id' => $part->id
                    ],
                    [
                        'period' => $period,
                        'purchase_group' => $purchase_group,
                        'qty_po' => $qty_po,
                        'qty_outstanding' => $qty_outstanding,
                        'delivery_date' => $delivery_date
                    ]
                );
                $imported++;
            } else {
                $failures[] = [
                    'period' => $period,
                    'po_number' => $po_number,
                    'purchase_group' => $purchase_group,
                    'kode_vendor' => $kode_vendor,
                    'item_code' => $item_code,
                    'qty_po' => $qty_po,
                    'qty_outstanding' => $qty_outstanding,
                    'raw_date' => $rawDate,
                    'error_message' => $part ? 'Vendor tidak ditemukan' : ($vendor ? 'Part tidak ditemukan' : 'Part & vendor tidak ditemukan')
                ];
                $skipped++;
            }
        }

        if (!empty($failures)) {
            UploadFailure::create([
                'module' => 'master_po',
                'raw_data' => json_encode($failures),
                'error_message' => "$skipped record gagal diupload",
                'uploaded_by' => Auth::id(),
            ]);
        }

        return redirect()
            ->route('po.index')
            ->with('success', "Upload selesai. $imported data berhasil diimpor, $skipped dilewati.");
    }
}
