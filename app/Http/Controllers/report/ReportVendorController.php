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

        $tanggal = request()->tanggal ?? date('Y-m-d');
        $kemarin = date('Y-m-d', strtotime($tanggal . ' -1 day'));
        $bulan = date('m', strtotime($tanggal));
        $tahun = date('Y', strtotime($tanggal));
        if (!strtotime($tanggalPilih)) {
            $tanggalPilih = date('Y-m-d');
        }

        $data = DB::table('parts')
            ->leftJoin(DB::raw("(
                    SELECT id, part_id, vendor_id, tanggal, fg, wip, rm, judgement, kategori_problem, detail_problem
                    FROM master_stock
                    WHERE tanggal = '$tanggal'
                ) AS ms
            "), function ($join) {
                $join->on('parts.id', '=', 'ms.part_id');
            })

            ->leftJoin('master_2hk', 'parts.id', '=', 'master_2hk.part_id')

            ->leftJoin(DB::raw("(
                SELECT part_id,
                    vendor_id,
                    SUM(qty_po) AS qty_po,
                    SUM(qty_outstanding) AS qty_outstanding
                FROM po_table
                WHERE MONTH(delivery_date) = $bulan
                AND YEAR(delivery_date) = $tahun
                GROUP BY part_id, vendor_id
            ) po"), 'parts.id', '=', 'po.part_id')

            ->leftJoin('vendors', function ($join) {
                $join->on('vendors.id', '=', DB::raw('COALESCE(ms.vendor_id, po.vendor_id)'));
            })

            ->leftJoin(DB::raw("(
                SELECT 
                    d.part_id,
                    p.vendor_id,
                    SUM(d.qty_plan) AS qty_plan,
                    SUM(d.qty_delivery) AS qty_delivery,
                    SUM(d.balance) AS balance,
                    SUM(d.qty_delay) AS qty_delay,
                    SUM(d.qty_manifest) AS qty_manifest
                FROM master_di d
                JOIN po_table p ON d.po_id = p.id
                WHERE MONTH(d.delivery_date) = $bulan
                AND YEAR(d.delivery_date) = $tahun
                AND DATE(d.delivery_date) <= '$kemarin'
                GROUP BY d.part_id, p.vendor_id
            ) di"), function ($join) {
                $join->on('parts.id', '=', 'di.part_id');
                $join->on(DB::raw('COALESCE(ms.vendor_id, po.vendor_id)'), '=', 'di.vendor_id');
            })
            
            ->select(
                'ms.part_id',
                'vendors.id as vendor_id',
                'vendors.nickname',
                'po.qty_po',
                'ms.judgement',
                'di.balance',
                'di.qty_plan',
                'ms.kategori_problem',
                'ms.rm',
                'ms.wip',
                'ms.fg'
            )
            ->orderBy('vendors.nickname', 'asc')
            ->whereDate('ms.tanggal', $tanggalPilih)
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

        $tanggalSebelumnya = DB::table('master_stock')
            ->where('tanggal', '<', $tanggalPilih)
            ->max('tanggal');

        if (!$tanggalSebelumnya) {
            $tanggalSebelumnya = null;
        }

        if ($tanggalSebelumnya) {
            $dataKemarin = DB::table('master_stock')
                ->select('vendor_id', 'part_id', 'rm', 'wip', 'fg')
                ->whereDate('tanggal', $tanggalSebelumnya)
                ->get()
                ->groupBy('vendor_id');
        } else {
            $dataKemarin = collect([]);
        }

        $grouped = $data->groupBy('vendor_id');

        $report = [];

        foreach ($grouped as $vendorId => $records) {

            $vendorName = $records->first()->nickname ?? '(Tidak ada nickname)';
            $recordsToday = collect($records);
            $recordsYesterday = $dataKemarin[$vendorId] ?? collect([]);

            if ($recordsYesterday->isEmpty()) {
                $vendorUpdated = true;
            } else {
                $vendorUpdated = false;

                foreach ($recordsToday as $rowToday) {
                    $rowYesterday = $recordsYesterday->firstWhere('part_id', $rowToday->part_id);

                    // part baru --> vendor update
                    if (!$rowYesterday) {
                        $vendorUpdated = true;
                        break;
                    }

                    // compare RM/WIP/FG
                    if (
                        $rowToday->rm != $rowYesterday->rm ||
                        $rowToday->wip != $rowYesterday->wip ||
                        $rowToday->fg != $rowYesterday->fg
                    ) {
                        $vendorUpdated = true;
                        break;
                    }
                }
            }

            foreach ($recordsToday as $rowToday) {
                $rowYesterday = $recordsYesterday->firstWhere('part_id', $rowToday->part_id);
                if (!$rowYesterday) continue;

                if (
                    $rowToday->rm != $rowYesterday->rm ||
                    $rowToday->wip != $rowYesterday->wip ||
                    $rowToday->fg != $rowYesterday->fg
                ) {
                    $vendorUpdated = true;
                    break;
                }
            }

            if (!$vendorUpdated) {
                $report[] = [
                    'vendor' => $vendorName,
                    'total_item' => 0,
                    'stok_ng' => 0,
                    'stok_ok' => 0,
                    'on_schedule' => 0,
                    'material' => 0,
                    'man' => 0,
                    'machine' => 0,
                    'method' => 0,
                    'konsistensi' => 0,
                    'akurasi_stok' => 0,
                    'akurasi_schedule' => 0,
                    'persen_material' => 0,
                    'persen_man' => 0,
                    'persen_machine' => 0,
                    'persen_method' => 0,
                ];
                continue;
            }

            $perPart = $recordsToday->groupBy('part_id')->map(function ($rows) {
                return [
                    'qty_po' => $rows->sum('qty_po'),
                    'judgement' => $rows->first()->judgement,
                    'balance' => $rows->sum('balance'),
                    'qty_plan' => $rows->sum('qty_plan'),
                    'kategori_problem' => $rows->first()->kategori_problem,
                ];
            });

            $total_item = $perPart->where('qty_po', '>', 0)->count();
            $stok_ok = $perPart->where('qty_po', '>', 0)->where('judgement', 'OK')->count();
            $stok_ng = $perPart->where('qty_po', '>', 0)->where('judgement', 'NG')->count();
            $on_schedule = $perPart->where('qty_plan', '>', 0)
                ->where('balance', '>=', 0)
                ->count();
            $material = $perPart->where('kategori_problem', 'Material')->count();
            $man = $perPart->where('kategori_problem', 'Man')->count();
            $machine = $perPart->where('kategori_problem', 'Machine')->count();
            $method = $perPart->where('kategori_problem', 'Method')->count();

            $konsistensi = $total_item > 0 ? 100 : 0;
            $akurasi_stok = $total_item > 0 ? round(($stok_ok / $total_item) * 100, 2) : 0;
            $akurasi_schedule = $total_item > 0 ? round(($on_schedule / $total_item) * 100, 2) : 0;
            $persen_material = $total_item > 0 ? round(($material / $total_item) * 100, 2) : 0;
            $persen_man = $total_item > 0 ? round(($man / $total_item) * 100, 2) : 0;
            $persen_machine = $total_item > 0 ? round(($machine / $total_item) * 100, 2) : 0;
            $persen_method = $total_item > 0 ? round(($method / $total_item) * 100, 2) : 0;

            $report[] = [
                'vendor' => $vendorName,
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
