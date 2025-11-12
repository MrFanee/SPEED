<?php

namespace App\Http\Controllers\stock;

use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class StockCreateController extends Controller
{
    public function create()
    {
        $today = now()->toDateString();

        // Cek apakah sudah ada data untuk hari ini
        $already = DB::table('master_stock')->whereDate('tanggal', $today)->exists();
        if ($already) {
            return back()->with('success', '2 Days Stock untuk hari ini sudah ada!');
        }

        // Ambil data terakhir
        $lastDate = DB::table('master_stock')->max('tanggal');

        if (!$lastDate) {
            return back()->with('success', 'Belum ada data sebelumnya untuk disalin.');
        }

        $data = DB::table('master_stock')->whereDate('tanggal', $lastDate)->get();

        foreach ($data as $row) {
            $new = (array) $row;
            unset($new['id']);

            $new['tanggal'] = $today;
            $new['created_at'] = now();
            $new['updated_at'] = now();

            DB::table('master_stock')->insert($new);
        }

        return back()->with('success', '2 Days Stock untuk hari ini berhasil dibuat!');
    }
}
