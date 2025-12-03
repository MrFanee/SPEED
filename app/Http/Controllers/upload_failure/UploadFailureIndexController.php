<?php

namespace App\Http\Controllers\upload_failure;

use App\Http\Controllers\Controller;

use App\UploadFailure;
use Illuminate\Support\Facades\DB;
use App\PO;
use App\Part;
use App\Vendor;

class UploadFailureIndexController extends Controller
{
    // list baris gagal
    public function index()
    {
        $failures = UploadFailure::orderBy('created_at', 'desc')->paginate(20);

        return view('upload_failure.index', compact('failures'));
    }

    // detail baris gagal
    public function show($id)
    {
        $failure = UploadFailure::findOrFail($id);

        return view('upload_failure.show', compact('failure'));
    }

    // upload ulang
    public function retry($id)
    {
        $failure = UploadFailure::findOrFail($id);
        $data = $failure->raw_data;

        try {
            DB::beginTransaction();

            if ($failure->module === 'master_po') {

                $part = Part::where('item_code', $data['item_code'])->first();
                $vendor = Vendor::where('kode_vendor', $data['kode_vendor'])->first();

                if (! $part || ! $vendor) {
                    return back()->with('error', 'Part atau vendor masih belum ada. Silakan lengkapi dulu.');
                }

                $delivery_date = null;
                if ($data['raw_date'] && preg_match('/\d{2}\/\d{2}\/\d{4}/', $data['raw_date'])) {
                    $delivery_date = \Carbon\Carbon::createFromFormat('d/m/Y', $data['raw_date'])->format('Y-m-d');
                }

                PO::updateOrCreate(
                    [
                        'po_number' => $data['po_number'],
                        'vendor_id' => $vendor->id,
                        'part_id' => $part->id
                    ],
                    [
                        'period' => $data['period'],
                        'purchase_group' => $data['purchase_group'],
                        'qty_po' => $data['qty_po'],
                        'qty_outstanding' => $data['qty_outstanding'],
                        'delivery_date' => $delivery_date
                    ]
                );
            }

            // hapus data gagal kalau berhasil
            $failure->delete();

            if ($failure->module === 'master_di') {
                DB::table('master_di')->insert([
                    'part_id' => $data['part_id'],
                    'vendor_id' => $data['vendor_id'],
                    'do_number' => $data['do_number'],
                    'qty' => $data['qty'],
                    'tanggal' => $data['tanggal'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            $failure->status = 'success';
            $failure->error_message = null;
            $failure->save();

            DB::commit();

            return redirect()->route('upload_failure.index')->with('success', 'Data berhasil diupload ulang.');
        } catch (\Exception $e) {
            DB::rollBack();

            // update error_message baru
            $failure->status = 'retry_failed';
            $failure->error_message = $e->getMessage();
            $failure->save();

            return redirect()->back()
                ->with('error', 'Gagal upload ulang: ' . $e->getMessage());
        }
    }
}
