<?php

namespace App\Http\Controllers\report;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class ReportVendorController extends Controller
{
    public function index(Request $request)
    {
        $tahunList = DB::table('master_stock')
            ->select(DB::raw('YEAR(tanggal) as tahun'))
            ->distinct()
            ->orderBy('tahun', 'desc')
            ->pluck('tahun');

        $tahun = $request->get('tahun', $tahunList->first());

        $bulanList = DB::table('master_stock')
            ->select(DB::raw('MONTH(tanggal) as bulan'))
            ->whereYear('tanggal', $tahun)
            ->distinct()
            ->orderBy('bulan', 'asc')
            ->pluck('bulan');

        $bulan = $request->get('bulan', $bulanList->first());

        $tanggalList = DB::table('master_stock')
            ->select(DB::raw('DAY(tanggal) as tanggal'))
            ->whereYear('tanggal', $tahun)
            ->whereMonth('tanggal', $bulan)
            ->distinct()
            ->orderBy('tanggal', 'asc')
            ->pluck('tanggal');

        $tanggal = $request->get('tanggal', $tanggalList->first());

        // Format ke YYYY-MM-DD
        $tanggalPilih = sprintf('%04d-%02d-%02d', $tahun, $bulan, $tanggal);

        // ambil semua data di tanggal tsb
        $data = DB::table('master_stock')
            ->join('vendors', 'master_stock.vendor_id', '=', 'vendors.id')
            ->join('parts', 'master_stock.part_id', '=', 'parts.id')
            ->leftJoin('po_table', 'po_table.part_id', '=', 'parts.id')
            ->leftJoin('master_di', 'po_table.id', '=', 'master_di.po_id')
            ->select(
                'vendors.nickname',
                'po_table.qty_po',
                'master_stock.judgement',
                'master_di.balance',
                'master_stock.kategori_problem'
            )
            ->whereDate('master_stock.tanggal', $tanggalPilih)
            ->get();

        if ($data->isEmpty()) {
            return view('report.vendor', [
                'tanggal' => $tanggal,
                'tanggalList' => $tanggalList,
                'report' => []
            ]);
        }

        // group berdasarkan vendor
        $grouped = $data->groupBy('nickname');

        $report = [];

        foreach ($grouped as $vendor => $records) {
            $records = collect($records);

            $total_item = $records->where('qty_po', '>', 0)->count();
            $stok_ok = $records->where('qty_po', '>', 0)->where('judgement', 'OK')->count();
            $stok_ng = $records->where('qty_po', '>', 0)->where('judgement', 'NG')->count();
            $on_schedule = $records->where('balance', '>=', 0)->count();

            $material = $records->where('kategori_problem', 'Material')->count();
            $man = $records->where('kategori_problem', 'Man')->count();
            $machine = $records->where('kategori_problem', 'Machine')->count();
            $method = $records->where('kategori_problem', 'Method')->count();

            $akurasi_stok = $total_item > 0 ? round(($stok_ok / $total_item) * 100, 2) : 0;
            $akurasi_schedule = $total_item > 0 ? round(($on_schedule / $total_item) * 100, 2) : 0;
            $persen_material = $total_item > 0 ? round(($material / $total_item) * 100, 2) : 0;
            $persen_man = $total_item > 0 ? round(($man / $total_item) * 100, 2) : 0;
            $persen_machine = $total_item > 0 ? round(($machine / $total_item) * 100, 2) : 0;
            $persen_method = $total_item > 0 ? round(($method / $total_item) * 100, 2) : 0;

            $report[] = [
                'tanggal' => $tanggal,
                'vendor' => $vendor ?? '(Tidak ada nickname)',
                'total_item' => $total_item,
                'stok_ng' => $stok_ng,
                'stok_ok' => $stok_ok,
                'on_schedule' => $on_schedule,
                'material' => $material,
                'man' => $man,
                'machine' => $machine,
                'method' => $method,
                'akurasi_stok' => $akurasi_stok,
                'akurasi_schedule' => $akurasi_schedule,
                'persen_material' => $persen_material,
                'persen_man' => $persen_man,
                'persen_machine' => $persen_machine,
                'persen_method' => $persen_method,
            ];
        }

        return view('report.vendor', compact(
            'tahun',
            'bulan',
            'tanggal',
            'tahunList',
            'bulanList',
            'tanggalList',
            'report'
        ));
    }
}
