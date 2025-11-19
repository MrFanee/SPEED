<?php

namespace App\Http\Controllers\report;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class ReportVendorController extends Controller
{
    public function index(Request $request)
    {
        $tanggalPilih = $request->get('tanggal_pilih', date('Y-m-d'));

        if (!strtotime($tanggalPilih)) {
            $tanggalPilih = date('Y-m-d');
        }

        $data = DB::table('master_stock')
            ->join('vendors', 'master_stock.vendor_id', '=', 'vendors.id')
            ->join('parts', 'master_stock.part_id', '=', 'parts.id')
            ->leftJoin('po_table', 'po_table.part_id', '=', 'parts.id')
            ->leftJoin('master_di', 'po_table.id', '=', 'master_di.po_id')
            ->select(
                'master_stock.part_id',
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
                'tanggalPilih' => $tanggalPilih,
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
                    'persen_material'  => 0,
                    'persen_man'       => 0,
                    'persen_machine'   => 0,
                    'persen_method'    => 0
                ]
            ]);
        }

        // group berdasarkan vendor
        $grouped = $data->groupBy('nickname');

        $report = [];

        foreach ($grouped as $vendor => $records) {
            $records = collect($records);

            $unique = $records->unique('part_id');

            $total_item = $unique->where('qty_po', '>', 0)->count();
            $stok_ok = $unique->where('qty_po', '>', 0)->where('judgement', 'OK')->count();
            $stok_ng = $unique->where('qty_po', '>', 0)->where('judgement', 'NG')->count();
            $on_schedule = $unique->where('balance', '>=', 0)->count();

            $material = $unique->where('kategori_problem', 'Material')->count();
            $man = $unique->where('kategori_problem', 'Man')->count();
            $machine = $unique->where('kategori_problem', 'Machine')->count();
            $method = $unique->where('kategori_problem', 'Method')->count();

            $konsistensi = $total_item > 0 ? 100 : 0;
            $akurasi_stok = $total_item > 0 ? round(($stok_ok / $total_item) * 100, 2) : 0;
            $akurasi_schedule = $total_item > 0 ? round(($on_schedule / $total_item) * 100, 2) : 0;
            $persen_material = $total_item > 0 ? round(($material / $total_item) * 100, 2) : 0;
            $persen_man = $total_item > 0 ? round(($man / $total_item) * 100, 2) : 0;
            $persen_machine = $total_item > 0 ? round(($machine / $total_item) * 100, 2) : 0;
            $persen_method = $total_item > 0 ? round(($method / $total_item) * 100, 2) : 0;

            $report[] = [
                'vendor' => $vendor ?? '(Tidak ada nickname)',
                'total_item' => $total_item,
                'stok_ng' => $stok_ng,
                'stok_ok' => $stok_ok,
                'on_schedule' => $on_schedule,
                'material' => $material,
                'man' => $man,
                'machine' => $machine,
                'method' => $method,
                'konsistensi' => $konsistensi,
                'akurasi_stok' => $akurasi_stok,
                'akurasi_schedule' => $akurasi_schedule,
                'persen_material' => $persen_material,
                'persen_man' => $persen_man,
                'persen_machine' => $persen_machine,
                'persen_method' => $persen_method,
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
            'persen_material'  => round(collect($report)->avg('persen_material'), 2),
            'persen_man'       => round(collect($report)->avg('persen_man'), 2),
            'persen_machine'   => round(collect($report)->avg('persen_machine'), 2),
            'persen_method'    => round(collect($report)->avg('persen_method'), 2),
        ];

        return view('report.vendor', compact(
            'tanggalPilih',
            'report',
            'summary'
        ));
    }
}
