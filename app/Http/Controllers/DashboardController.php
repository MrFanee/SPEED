<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(){
        $tanggalHariIni = date('Y-m-d');
        $tanggalKemarin = date('Y-m-d', strtotime('-1 day'));

        $cardData = $this->getCardData($tanggalHariIni);

        $chartData = $this->getChartData($tanggalHariIni, $tanggalKemarin);

        $lastUpdates = DB::table('vendors')
            ->leftJoin('master_stock', 'vendors.id', '=', 'master_stock.vendor_id')
            ->select(
                'vendors.nickname as vendor',
                DB::raw('MAX(master_stock.updated_at) as last_update'))
            ->groupBy('vendors.id', 'vendors.nickname')
            ->orderBy('last_update', 'desc')
            ->get();

        return view('dashboard', compact('cardData', 'chartData', 'lastUpdates'));
    }

    private function getCardData($tanggal){
        $data = DB::table('master_stock')
            ->join('vendors', 'master_stock.vendor_id', '=', 'vendors.id')
            ->join('parts', 'master_stock.part_id', '=', 'parts.id')
            ->leftJoin('po_table', 'po_table.part_id', '=', 'parts.id')
            ->leftJoin('master_di', 'po_table.id', '=', 'master_di.po_id')
            ->select(
                'master_stock.part_id',
                'master_stock.judgement',
                'master_stock.kategori_problem',
                'master_di.balance'
            )
            ->whereDate('master_stock.tanggal', $tanggal)
            ->get();

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
                'kategori_problem' => $rows->first()->kategori_problem,
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

    private function getChartData($tanggalHariIni, $tanggalKemarin){
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
            ->whereDate('master_stock.tanggal', $tanggalHariIni)
            ->get();

        $dataKemarin = DB::table('master_stock')
            ->select('vendor_id', 'part_id', 'rm', 'wip', 'fg')
            ->whereDate('tanggal', $tanggalKemarin)
            ->get()
            ->groupBy(['vendor_id', 'part_id']);

        $vendorsUpdated = $this->getVendorsUpdated($dataHariIni, $dataKemarin);
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

    private function getVendorsUpdated($dataHariIni, $dataKemarin){
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
