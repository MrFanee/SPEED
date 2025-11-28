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

            DB::table('master_stock')->insert($new);
        }

        return back()->with('success', "2 Days Stock untuk tanggal $tanggal berhasil dibuat!");
    }
}
