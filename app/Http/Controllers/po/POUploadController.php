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

            $po_number = trim($row[0]);
            $qty_po = trim($row[1]);
            $qty_outstanding = trim($row[2]);
            $status = trim($row[3]);
            $item_code = trim($row[4]);
            $vendor_name = trim($row[5]);

            $part = Part::where('item_code', $item_code)->first();
            $vendor = Vendor::where('vendor_name', $vendor_name)->first();

            if ($part && $vendor) {
                PO::updateOrCreate(
                    [
                        'part_id' => $part->id,
                        'vendor_id' => $vendor->id
                    ],
                    [
                        'po_number' => $po_number,
                        'qty_po' => $qty_po,
                        'qty_outstanding' => $qty_outstanding,
                        'status' => $status
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
