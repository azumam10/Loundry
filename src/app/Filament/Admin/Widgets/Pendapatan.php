<?php

namespace App\Filament\Admin\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\Transaksi;
use Illuminate\Support\Facades\DB;

class Pendapatan extends ChartWidget
{
    protected static ?string $heading = 'Grafik Pendapatan Bulanan';

    protected function getData(): array
    {
        $data = Transaksi::selectRaw('MONTH(tanggal) as bulan, SUM(total) as total')
            ->where('status_pembayaran', 'lunas')
            ->groupBy(DB::raw('MONTH(tanggal)'))
            ->orderBy('bulan')
            ->get();

        $bulanLabels = [];
        $totals = [];
        $colors = [];

        if ($data->isEmpty()) {
            return [
                'datasets' => [],
                'labels' => [],
            ];
        }

        // Hitung rata-rata
        $average = $data->avg('total');

        foreach ($data as $row) {
            $bulanLabels[] = date('F', mktime(0, 0, 0, $row->bulan, 10));
            $totals[] = $row->total;

            // Warna dinamis
            if ($row->total >= $average * 1.2) {
                $colors[] = 'rgba(0, 200, 0, 0.8)'; // hijau - tinggi
            } elseif ($row->total <= $average * 0.8) {
                $colors[] = 'rgba(255, 50, 50, 0.8)'; // merah - rendah
            } else {
                $colors[] = 'rgba(255, 200, 0, 0.8)'; // kuning - rata-rata
            }
        }

        return [
            'datasets' => [
                [
                    'label' => 'Pendapatan',
                    'data' => $totals,
                    'backgroundColor' => $colors,
                    'borderColor' => 'rgba(0,0,0,0.2)',
                    'borderWidth' => 1,
                ],
            ],
            'labels' => $bulanLabels,
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
