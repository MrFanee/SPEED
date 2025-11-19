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
            $po_number = trim($row[2]);
            $qty_plan = trim($row[3]);
            $qty_delivery = trim($row[4]);

            $po = PO::where('po_number', $po_number)->first();

            if ($po) {
                DI::updateOrCreate(
                    ['po_id' => $po->id],
                    [
                        'item_code' => $item_code, 
                        'part_name' => $part_name,
                        'qty_plan' => $qty_plan, 
                        'qty_delivery' => $qty_delivery]
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
