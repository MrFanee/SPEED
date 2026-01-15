<?php

namespace App\Http\Controllers\report;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ReportMonthlyController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        $tanggal = request()->tanggal ?? date('Y-m-d');
        $kemarin = date('Y-m-d', strtotime($tanggal . ' -1 day'));
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

        if ($user->role === 'vendor') {
            $vendor = DB::table('vendors')
                ->where('id', $user->vendor_id)
                ->value('nickname');
            $vendorName = DB::table('vendors')
                ->where('id', $user->vendor_id)
                ->value('vendor_name');
        } else {
            $vendorName = DB::table('vendors')
                ->where('nickname', $vendor)
                ->value('vendor_name');
        }

        $data = DB::table('parts')
            ->leftJoin('master_2hk as std', 'parts.id', '=', 'std.part_id')

            ->leftJoin(DB::raw("(
                    SELECT id, part_id, vendor_id, tanggal, fg, wip, rm, judgement, kategori_problem, detail_problem
                    FROM master_stock
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
                'ms.fg',
                'ms.tanggal as tgl'
            )
            ->whereYear('ms.tanggal', $tahun)
            ->whereMonth('ms.tanggal', $bulan)
            ->whereNotNull('std.std_stock')
            ->where('std.std_stock', '>', 0);

        if ($user->role === 'vendor') {
            $data->where('ms.vendor_id', $user->vendor_id);
        } else {
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

            $tanggalSebelumnya = DB::table('master_stock')
                ->where('tanggal', '<', $tanggal)
                ->max('tanggal');

            $dataSebelumnya = collect([]);

            if ($tanggalSebelumnya) {
                $dataSebelumnya = DB::table('master_stock')
                    ->select('vendor_id', 'part_id', 'rm', 'wip', 'fg')
                    ->whereDate('tanggal', $tanggalSebelumnya)
                    ->where('vendor_id', $user->role === 'vendor' ? $user->vendor_id : function ($q) use ($vendor) {
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
                    'qty_plan' => $rows->sum('qty_plan'),
                    'balance' => $rows->sum('balance'),
                    'kategori_problem' => $rows->first()->kategori_problem,
                ];
            });
            $ng = $perPart->where('qty_po', '>', 0)->where('judgement', 'NG');


            $total_item = $perPart->where('qty_po', '>', 0)->count();
            $stok_ok = $perPart->where('qty_po', '>', 0)->where('judgement', 'OK')->count();
            $stok_ng = $perPart->where('qty_po', '>', 0)->where('judgement', 'NG')->count();
            $on_schedule = $perPart->where('qty_plan', '>', 0)
                ->where('balance', '>=', 0)
                ->count();
            $material = $ng->where('kategori_problem', 'Material')->count();
            $man = $ng->where('kategori_problem', 'Man')->count();
            $machine = $ng->where('kategori_problem', 'Machine')->count();
            $method = $ng->where('kategori_problem', 'Method')->count();

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
