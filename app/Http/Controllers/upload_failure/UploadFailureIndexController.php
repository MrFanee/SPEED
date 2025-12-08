<?php

namespace App\Http\Controllers\upload_failure;

use App\Http\Controllers\Controller;

use App\UploadFailure;
use App\PO;
use App\DI;
use App\Part;
use App\Vendor;

class UploadFailureIndexController extends Controller
{
    // list file gagal
    public function index()
    {
        $failures = UploadFailure::orderBy('created_at', 'desc')->paginate(20);

        return view('upload_failure.index', compact('failures'));
    }

    // detail baris gagal
    public function show($id)
    {
        $failure = UploadFailure::findOrFail($id);
        $rows = json_decode($failure->raw_data, true);
        $headers = [
            'raw_date'      => 'Delv. Date',
            'item_code'     => 'Item Code',
            'part_name'     => 'Part Name',
            'vendor_code'   => 'Kode Vendor',
            'vendor_name'   => 'Vendor Name',
            'po_number'     => 'PO Number',
            'qty_plan'      => 'Qty. Plan',
            'qty_delivery'  => 'Qty. Delv.',
            'purchase_group' => 'Purch. Group',
            'qty_po'        => 'Qty. PO',
            'qty_outstanding' => 'OS PO',
            'error_message' => 'Reason',
        ];

        return view('upload_failure.show', compact('failure', 'rows', 'headers'));
    }

    // upload ulang
    public function retry($id)
    {
        $failure = UploadFailure::findOrFail($id);
        $rows = json_decode($failure->raw_data, true);

        $stillFails = [];

        foreach ($rows as $data) {

            if ($failure->module == 'master_po') {

                $vendor = Vendor::where('kode_vendor', $data['kode_vendor'])->first();
                $part = Part::where('item_code', $data['item_code'])->first();

                if ($vendor && $part) {
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
                            'delivery_date' => $data['raw_date'],
                        ]
                    );
                } else {
                    $stillFails[] = $data;
                }
            }

            if ($failure->module == 'master_di') {

                $part = Part::where('item_code', $data['item_code'])->first();

                $po = PO::where('po_number', $data['po_number'])
                    ->where('part_id', $part->id)
                    ->first();

                if ($po && $part) {
                    DI::updateOrCreate(
                        [
                            'po_id' => $po->id,
                            'part_id' => $part->id
                        ],
                        [
                            'delivery_date' => $data['raw_date'],
                            'part_name' => $data['part_name'],
                            'qty_plan' => $data['qty_plan'],
                            'qty_delivery' => $data['qty_delivery'],
                        ]
                    );
                } else {
                    $stillFails[] = $data;
                }
            }
        }

        if (empty($stillFails)) {
            $failure->delete();
        } else {
            $failure->raw_data = json_encode($stillFails);
            $failure->error_message = count($stillFails) . " data masih gagal";
            $failure->save();
        }

        return redirect()->route('upload_failure.index')
            ->with('success', 'Reupload selesai.');
    }
}
