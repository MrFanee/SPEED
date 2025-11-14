<?php

namespace App\Http\Controllers\report;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class ReportMonthlyController extends Controller
{
    public function index(Request $request)
    {
        $vendorList = DB::table('vendors')
            ->orderBy('nickname')
            ->pluck('nickname');

        $vendor = $request->get('vendor', $vendorList->first());

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
            ->orderBy('bulan', 'desc')
            ->pluck('bulan');

        $bulan = $request->get('bulan', $bulanList->first());

        $vendorName = DB::table('vendors')
            ->where('nickname', $vendor)
            ->value('vendor_name');

        $data = DB::table('master_stock')
            ->join('vendors', 'master_stock.vendor_id', '=', 'vendors.id')
            ->join('parts', 'master_stock.part_id', '=', 'parts.id')
            ->leftJoin('po_table', 'po_table.part_id', '=', 'parts.id')
            ->leftJoin('master_di', 'po_table.id', '=', 'master_di.po_id')
            ->select(
                DB::raw('DATE(master_stock.tanggal) as tgl'),
                'po_table.qty_po',
                'master_stock.judgement',
                'master_di.balance',
                'master_stock.kategori_problem'
            )
            ->whereYear('master_stock.tanggal', $tahun)
            ->whereMonth('master_stock.tanggal', $bulan);

        if ($vendor) {
            $data->where('vendors.nickname', $vendor);
        }

        $data = $data->get();

        if ($data->isEmpty()) {
            return view('report.monthly', [
                'report' => [],
                'summary' => [
                    'total_item'     => 0,
                    'stok_ng'        => 0,
                    'stok_ok'        => 0,
                    'on_schedule'    => 0,
                    'material'       => 0,
                    'man'            => 0,
                    'machine'        => 0,
                    'method'         => 0,
                    'konsistensi'      => 0,
                    'akurasi_stok'     => 0,
                    'akurasi_schedule' => 0,
                    // 'persen_material'  => 0, 
                    // 'persen_man'       => 0,
                    // 'persen_machine'   => 0,
                    // 'persen_method'    => 0,
                ],
                'vendorList' => $vendorList,
                'tahunList' => $tahunList,
                'bulanList' => $bulanList,
                'vendor' => $vendor,
                'tahun' => $tahun,
                'bulan' => $bulan,
                'vendorName' => $vendorName,
            ]);
        }

        // Group berdasarkan tanggal
        $grouped = $data->groupBy('tgl');

        $report = [];

        foreach ($grouped as $tanggal => $records) {
            $records = collect($records);

            $total_item = $records->where('qty_po', '>', 0)->count();
            $stok_ok = $records->where('qty_po', '>', 0)->where('judgement', 'OK')->count();
            $stok_ng = $records->where('qty_po', '>', 0)->where('judgement', 'NG')->count();
            $on_schedule = $records->where('balance', '>=', 0)->count();

            $material = $records->where('kategori_problem', 'Material')->count();
            $man = $records->where('kategori_problem', 'Man')->count();
            $machine = $records->where('kategori_problem', 'Machine')->count();
            $method = $records->where('kategori_problem', 'Method')->count();

            $report[] = [
                'tanggal' => $tanggal,
                'total_item' => $total_item,
                'stok_ok' => $stok_ok,
                'stok_ng' => $stok_ng,
                'on_schedule' => $on_schedule,
                'material' => $material,
                'man' => $man,
                'machine' => $machine,
                'method' => $method,
                'konsistensi' => $total_item > 0 ? 100 : 0,
                'akurasi_stok' => $total_item > 0 ? round(($stok_ok / $total_item) * 100, 2) : 0,
                'akurasi_schedule' => $total_item > 0 ? round(($on_schedule / $total_item) * 100, 2) : 0,
                // 'persen_material' => $total_item > 0 ? round(($material / $total_item) * 100, 2) : 0,
                // 'persen_man' => $total_item > 0 ? round(($man / $total_item) * 100, 2) : 0,
                // 'persen_machine' => $total_item > 0 ? round(($machine / $total_item) * 100, 2) : 0,
                // 'persen_method' => $total_item > 0 ? round(($method / $total_item) * 100, 2) : 0,
            ];
        }

        $summary = [
            'total_item'     => collect($report)->sum('total_item'),
            'stok_ng'        => collect($report)->sum('stok_ng'),
            'stok_ok'        => collect($report)->sum('stok_ok'),
            'on_schedule'    => collect($report)->sum('on_schedule'),
            'material'       => collect($report)->sum('material'),
            'man'            => collect($report)->sum('man'),
            'machine'        => collect($report)->sum('machine'),
            'method'         => collect($report)->sum('method'),

            'konsistensi'      => round(collect($report)->avg('konsistensi'), 2),
            'akurasi_stok'     => round(collect($report)->avg('akurasi_stok'), 2),
            'akurasi_schedule' => round(collect($report)->avg('akurasi_schedule'), 2),
            // 'persen_material'  => round(collect($report)->avg('persen_material'), 2),
            // 'persen_man'       => round(collect($report)->avg('persen_man'), 2),
            // 'persen_machine'   => round(collect($report)->avg('persen_machine'), 2),
            // 'persen_method'    => round(collect($report)->avg('persen_method'), 2),
        ];

        return view('report.monthly', compact(
            'tahun',
            'bulan',
            'vendor',
            'vendorList',
            'vendorName',
            'tahunList',
            'bulanList',
            'report',
            'summary'
        ));
    }
}
