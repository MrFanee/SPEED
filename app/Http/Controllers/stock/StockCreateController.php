<?php

namespace App\Http\Controllers\stock;

use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class StockCreateController extends Controller
{
    public function create()
    {
        $tanggal = request('tanggal') ?? now()->toDateString();

        // Cek apakah tanggal itu sudah ada data
        $already = DB::table('master_stock')
            ->whereDate('tanggal', $tanggal)
            ->leftJoin('parts', 'master_stock.part_id', '=', 'parts.id')
            ->leftJoin('vendors', 'master_stock.vendor_id', '=', 'vendors.id')
            ->leftJoin('master_2hk', 'parts.id', '=', 'master_2hk.part_id')
            ->leftJoin('po_table', function ($join) {
                $join->on('parts.id', '=', 'po_table.part_id')
                    ->on('master_stock.vendor_id', '=', 'po_table.vendor_id')
                    ->whereMonth('po_table.delivery_date', date('m'))
                    ->whereYear('po_table.delivery_date', date('Y'));
            })
            ->leftJoin('master_di', function ($join) {
                $join->on('po_table.id', '=', 'master_di.po_id')
                    ->whereMonth('master_di.delivery_date', date('m'))
                    ->whereYear('master_di.delivery_date', date('Y'));
            })
            ->update([
                'judgement' => DB::raw("
                    CASE
                        WHEN qty_po = 0 THEN 'NO PO'
                        WHEN fg >= std_stock THEN 'OK'
                        WHEN fg < std_stock THEN 'NG'
                        ELSE '-'
                    END
                ")
            ]);
        if ($already) {
            return back()->with('success', "2 Days Stock untuk tanggal $tanggal sudah ada!");
        }

        // Ambil data terakhir yang tersedia (hari sebelumnya)
        $lastDate = DB::table('master_stock')->max('tanggal');

        if (!$lastDate) {
            return back()->with('success', 'Belum ada data sebelumnya untuk disalin.');
        }

        $data = DB::table('master_stock')->whereDate('tanggal', $lastDate)->get();

        foreach ($data as $row) {
            $new = (array) $row;
            unset($new['id']);

            $new['tanggal'] = $tanggal;
            $new['created_at'] = now();
            $new['updated_at'] = now();
            $new['vendor_updated_at'] = null;

            $exists = DB::table('master_stock')
                ->where('part_id', $new['part_id'])
                ->where('vendor_id', $new['vendor_id'])
                ->whereDate('tanggal', $tanggal)
                ->exists();

            if (!$exists) {
                DB::table('master_stock')->insert($new);
            }
        }

        $allParts = DB::table('po_table')
            ->select('part_id', 'vendor_id')
            ->whereMonth('delivery_date', date('m'))
            ->whereYear('delivery_date', date('Y'))
            ->groupBy('part_id', 'vendor_id')
            ->get();

        foreach ($allParts as $p) {
            $exists = DB::table('master_stock')
                ->where('part_id', $p->part_id)
                ->where('vendor_id', $p->vendor_id)
                ->whereDate('tanggal', $tanggal)
                ->exists();

            if (!$exists) {
                $po = DB::table('po_table')
                    ->where('part_id', $p->part_id)
                    ->whereMonth('delivery_date', date('m'))
                    ->whereYear('delivery_date', date('Y'))
                    ->sum('qty_po');

                $std = DB::table('master_2hk')->where('part_id', $p->part_id)->max('std_stock');

                $fg = 0;

                if ($po == 0) $judgement = 'NO PO';
                elseif ($fg >= $std) $judgement = 'OK';
                else $judgement = 'NG';

                DB::table('master_stock')->insert([
                    'part_id' => $p->part_id,
                    'vendor_id' => $p->vendor_id,
                    'tanggal' => $tanggal,
                    'fg' => 0,
                    'wip' => 0,
                    'rm' => 0,
                    'judgement' => $judgement,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        return back()->with('success', "2 Days Stock untuk tanggal $tanggal berhasil dibuat!");
    }
}
