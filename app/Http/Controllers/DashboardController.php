<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $tanggal = request('tanggal') ?? date('Y-m-d');

        \Carbon\Carbon::setLocale('id');

        $formattedTanggal = \Carbon\Carbon::parse($tanggal)->translatedFormat('d M Y');
        $tanggalHariIni = $tanggal;
        $tanggalKemarin = date('Y-m-d', strtotime($tanggal . ' -1 day'));

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

        $cardData = $this->getCardData($tanggalHariIni);
        $chartData = $this->getChartData($tanggalHariIni, $tanggalKemarin);
        $monthlyResume = $this->getMonthlyResume($bulan, $tahun);

        $lastUpdates = DB::table('vendors')
            ->leftJoin('master_stock', 'vendors.id', '=', 'master_stock.vendor_id')
            ->select(
                'vendors.nickname as vendor',
                DB::raw('MAX(master_stock.vendor_updated_at) as last_update')
            )
            ->groupBy('vendors.id', 'vendors.nickname')
            ->orderBy('last_update', 'desc');

        if (Auth::user()->role === 'vendor') {
            $lastUpdates->where('master_stock.vendor_id', Auth::user()->vendor_id);
        }

        $lastUpdates = $lastUpdates->get();

        return view('dashboard', compact('cardData', 'chartData', 'lastUpdates', 'monthlyResume', 'formattedTanggal', 'tahunList', 'tahun', 'bulanList', 'bulan'));
    }

    private function getCardData($tanggal)
    {
        $data = DB::table('master_stock')
            ->join('vendors', 'master_stock.vendor_id', '=', 'vendors.id')
            ->join('parts', 'master_stock.part_id', '=', 'parts.id')
            ->leftJoin('po_table', 'po_table.part_id', '=', 'parts.id')
            ->leftJoin('master_di', 'po_table.id', '=', 'master_di.po_id')
            ->select(
                'master_stock.part_id',
                'master_stock.judgement',
                'master_stock.kategori_problem',
                'master_di.balance',
                'master_di.qty_plan',
                // 'po_table.qty_po'
            )
            ->whereDate('master_stock.tanggal', $tanggal);

        if (Auth::user()->role === 'vendor') {
            $data->where('master_stock.vendor_id', Auth::user()->vendor_id);
        }

        $data = $data->get();

        if ($data->isEmpty()) {
            return [
                'total_item' => 0,
                'total_ng' => 0,
                'total_ok' => 0,
                'total_on_schedule' => 0,
                'total_material' => 0,
                'total_man' => 0,
                'total_machine' => 0,
                'total_method' => 0
            ];
        }

        $perPart = $data->groupBy('part_id')->map(function ($rows) {
            return [
                'judgement' => $rows->first()->judgement,
                'balance' => $rows->sum('balance'),
                // 'qty_plan' => $rows->$rows->first()->qty_plan,
                'kategori_problem' => $rows->first()->kategori_problem,
                // 'qty_po' => $rows->first()->qty_po
            ];
        });

        return [
            'total_item' => $perPart->count(),
            'total_ng' => $perPart->where('judgement', 'NG')->count(),
            'total_ok' => $perPart->where('judgement', 'OK')->count(),
            'total_on_schedule' => $perPart->where('balance', '>=', 0)->count(),
            'total_material' => $perPart->where('kategori_problem', 'Material')->count(),
            'total_man' => $perPart->where('kategori_problem', 'Man')->count(),
            'total_machine' => $perPart->where('kategori_problem', 'Machine')->count(),
            'total_method' => $perPart->where('kategori_problem', 'Method')->count()
        ];
    }

    private function getChartData($tanggalHariIni, $tanggalKemarin)
    {
        $today = date('Y-m-d');

        $allVendors = DB::table('vendors')
            ->select('id', 'nickname')
            ->orderBy('nickname', 'asc')
            ->get();

        $dataHariIni = DB::table('master_stock')
            ->join('vendors', 'master_stock.vendor_id', '=', 'vendors.id')
            ->join('parts', 'master_stock.part_id', '=', 'parts.id')
            ->select(
                'vendors.id as vendor_id',
                'vendors.nickname',
                'master_stock.part_id',
                'master_stock.judgement'
            )
            ->whereDate('master_stock.tanggal', $tanggalHariIni);

        if (Auth::user()->role === 'vendor') {
            $dataHariIni->where('master_stock.vendor_id', Auth::user()->vendor_id);
        }

        $dataHariIni = $dataHariIni->get();

        $dataKemarin = DB::table('master_stock')
            ->select('vendor_id', 'part_id', 'rm', 'wip', 'fg')
            ->whereDate('tanggal', $tanggalKemarin)
            ->get()
            ->groupBy(['vendor_id', 'part_id']);

        // $vendorsUpdated = $this->getVendorsUpdated($dataHariIni, $dataKemarin);
        $groupedByVendor = $dataHariIni->groupBy('vendor_id');

        $chartData = [];

        foreach ($allVendors as $vendor) {
            $vendorId = $vendor->id;
            $vendorName = $vendor->nickname;

            // data vendor hari ini
            $vendorRecords = $groupedByVendor[$vendorId] ?? collect([]);

            if ($vendorRecords->isEmpty()) {
                $chartData[] = [
                    'vendor' => $vendorName,
                    'item_ng' => 0,
                    'item_ok' => 0
                ];
                continue;
            }

            // $lastUpdate = DB::table('master_stock')
            //     ->where('vendor_id', $vendorId)
            //     ->max('vendor_updated_at');

            // $updatedToday = $lastUpdate && date('Y-m-d', strtotime($lastUpdate)) === $today;

            // if (!$updatedToday) {
            //     $chartData[] = [
            //         'vendor' => $vendorName,
            //         'item_ng' => 0,
            //         'item_ok' => 0
            //     ];
            //     continue;
            // }

            $perPart = $vendorRecords->groupBy('part_id');

            $item_ng = $perPart->filter(fn($rows) => optional($rows->first())->judgement === 'NG')->count();

            $item_ok = $perPart->filter(fn($rows) => optional($rows->first())->judgement === 'OK')->count();

            $chartData[] = [
                'vendor' => $vendorName,
                'item_ng' => $item_ng,
                'item_ok' => $item_ok
            ];
        }

        return $chartData;
    }

    private function getMonthlyResume($bulan, $tahun)
    {
        $start = date("$tahun-$bulan-01");
        $end   = date("Y-m-t", strtotime($start));

        $data = DB::table('master_stock')
            ->select(
                DB::raw('DATE(tanggal) as tgl'),
                DB::raw("SUM(CASE WHEN judgement = 'OK' THEN 1 ELSE 0 END) as ok"),
                DB::raw("SUM(CASE WHEN judgement = 'NG' AND (kategori_problem IS NULL OR kategori_problem = '') THEN 1 ELSE 0 END) as ng_no_category"),
                DB::raw("SUM(CASE WHEN judgement = 'NG' AND kategori_problem = 'Material' THEN 1 ELSE 0 END) as material"),
                DB::raw("SUM(CASE WHEN judgement = 'NG' AND kategori_problem = 'Man' THEN 1 ELSE 0 END) as man"),
                DB::raw("SUM(CASE WHEN judgement = 'NG' AND kategori_problem = 'Machine' THEN 1 ELSE 0 END) as machine"),
                DB::raw("SUM(CASE WHEN judgement = 'NG' AND kategori_problem = 'Method' THEN 1 ELSE 0 END) as method"),
                DB::raw("COUNT(DISTINCT part_id) as total_item")
            )
            ->whereBetween('tanggal', [$start, $end])
            ->groupBy(DB::raw('DATE(tanggal)'))
            ->orderBy('tgl');

        if (Auth::user()->role === 'vendor') {
            $data->where('master_stock.vendor_id', Auth::user()->vendor_id);
        }

        // $data->whereNotNull('vendor_updated_at');
        // $data->whereDate('vendor_updated_at', '>=', $start);

        $data = $data->get();

        return $data;
    }

    private function getVendorsUpdated($dataHariIni, $dataKemarin)
    {
        if ($dataKemarin->isEmpty()) {
            return $dataHariIni->pluck('vendor_id')->unique()->toArray();
        }

        $vendorsUpdated = [];
        $groupedHariIni = $dataHariIni->groupBy('vendor_id');

        foreach ($groupedHariIni as $vendorId => $recordsToday) {
            if (!isset($dataKemarin[$vendorId])) {
                $vendorsUpdated[] = $vendorId;
                continue;
            }

            $recordsYesterday = $dataKemarin[$vendorId];

            foreach ($recordsToday as $rowToday) {
                $partId = $rowToday->part_id;

                if (!isset($recordsYesterday[$partId])) {
                    $vendorsUpdated[] = $vendorId;
                    break;
                }
            }
        }

        return array_unique($vendorsUpdated);
    }
}
