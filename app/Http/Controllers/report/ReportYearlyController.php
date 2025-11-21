<?php

namespace App\Http\Controllers\report;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class ReportYearlyController extends Controller
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

        $vendorName = DB::table('vendors')
            ->where('nickname', $vendor)
            ->value('vendor_name');

        $monthlyReport = [];

        for ($bulan = 1; $bulan <= 12; $bulan++) {

            $data = DB::table('master_stock')
                ->join('vendors', 'master_stock.vendor_id', '=', 'vendors.id')
                ->join('parts', 'master_stock.part_id', '=', 'parts.id')
                ->leftJoin('po_table', 'po_table.part_id', '=', 'parts.id')
                ->leftJoin('master_di', 'po_table.id', '=', 'master_di.po_id')
                ->select(
                    DB::raw('DATE(master_stock.tanggal) as tgl'),
                    'master_stock.part_id',
                    'po_table.qty_po',
                    'master_stock.judgement',
                    'master_di.balance',
                    'master_stock.kategori_problem',
                    'master_stock.rm',
                    'master_stock.wip',
                    'master_stock.fg'
                )
                ->whereYear('master_stock.tanggal', $tahun)
                ->whereMonth('master_stock.tanggal', $bulan);

            if ($vendor) {
                $data->where('vendors.nickname', $vendor);
            }

            $data = $data->get();

            if ($data->isEmpty()) {
                continue;
            }

            $grouped = $data->groupBy('tgl');

            $report = [];
            foreach ($grouped as $tanggal => $records) {
                $records = collect($records);

                $tanggalSebelumnya = DB::table('master_stock')
                    ->where('tanggal', '<', $tanggal)
                    ->max('tanggal');

                $dataSebelumnya = collect([]);

                if ($tanggalSebelumnya) {
                    $dataSebelumnya = DB::table('master_stock')
                        ->select('vendor_id', 'part_id', 'rm', 'wip', 'fg')
                        ->whereDate('tanggal', $tanggalSebelumnya)
                        ->where('vendor_id', function ($q) use ($vendor) {
                            $q->from('vendors')->select('id')->where('nickname', $vendor);
                        })
                        ->get();
                }

                $updated = false;

                foreach ($records as $row) {
                    $prev = $dataSebelumnya->firstWhere('part_id', $row->part_id);

                    if (!$prev) {
                        $updated = true;
                        break;
                    }

                    if ($prev->rm != $row->rm || $prev->wip != $row->wip || $prev->fg != $row->fg) {
                        $updated = true;
                        break;
                    }
                }

                // Jika tidak update → report 0
                if (!$updated) {
                    $report[] = [
                        'tanggal' => $tanggal,
                        'total_item' => 0,
                        'stok_ok' => 0,
                        'stok_ng' => 0,
                        'on_schedule' => 0,
                        'material' => 0,
                        'man' => 0,
                        'machine' => 0,
                        'method' => 0,
                        'konsistensi' => 0,
                        'akurasi_stok' => 0,
                        'akurasi_schedule' => 0,
                    ];
                    continue;
                }

                $perPart = $records->groupBy('part_id')->map(function ($rows) {
                    return [
                        'qty_po' => $rows->sum('qty_po'),
                        'judgement' => $rows->first()->judgement,
                        'balance' => $rows->sum('balance'),
                        'kategori_problem' => $rows->first()->kategori_problem,
                    ];
                });

                $total_item = $perPart->where('qty_po', '>', 0)->count();
                $stok_ok = $perPart->where('qty_po', '>', 0)->where('judgement', 'OK')->count();
                $stok_ng = $perPart->where('qty_po', '>', 0)->where('judgement', 'NG')->count();
                $on_schedule = $perPart->where('balance', '>=', 0)->count();

                $material = $perPart->where('kategori_problem', 'Material')->count();
                $man = $perPart->where('kategori_problem', 'Man')->count();
                $machine = $perPart->where('kategori_problem', 'Machine')->count();
                $method = $perPart->where('kategori_problem', 'Method')->count();

                $report[] = [
                    'bulan' => $bulan,
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
                ];
            }

            $summaryBulanan = [
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
            ];

            $monthlyReport[] = [
                'bulan' => $bulan,
                'total_item' => $summaryBulanan['total_item'],
                'stok_ng' => $summaryBulanan['stok_ng'],
                'stok_ok' => $summaryBulanan['stok_ok'],
                'on_schedule' => $summaryBulanan['on_schedule'],
                'material' => $summaryBulanan['material'],
                'man' => $summaryBulanan['man'],
                'machine' => $summaryBulanan['machine'],
                'method' => $summaryBulanan['method'],
                'konsistensi' => $summaryBulanan['konsistensi'],
                'akurasi_stok' => $summaryBulanan['akurasi_stok'],
                'akurasi_schedule' => $summaryBulanan['akurasi_schedule'],
            ];
        }

        $summary = [
            'total_item'     => collect($monthlyReport)->sum('total_item'),
            'stok_ng'        => collect($monthlyReport)->sum('stok_ng'),
            'stok_ok'        => collect($monthlyReport)->sum('stok_ok'),
            'on_schedule'    => collect($monthlyReport)->sum('on_schedule'),
            'material'       => collect($monthlyReport)->sum('material'),
            'man'            => collect($monthlyReport)->sum('man'),
            'machine'        => collect($monthlyReport)->sum('machine'),
            'method'         => collect($monthlyReport)->sum('method'),

            'konsistensi'      => round(collect($monthlyReport)->avg('konsistensi'), 2),
            'akurasi_stok'     => round(collect($monthlyReport)->avg('akurasi_stok'), 2),
            'akurasi_schedule' => round(collect($monthlyReport)->avg('akurasi_schedule'), 2),
        ];

        return view('report.yearly', [
            'tahun' => $tahun,
            'vendor' => $vendor,
            'vendorList' => $vendorList,
            'vendorName' => $vendorName,
            'tahunList' => $tahunList,
            'report' => $monthlyReport,
            'summary' => $summary
        ]);
    }
}
