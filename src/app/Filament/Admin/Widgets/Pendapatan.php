<?php

namespace App\Filament\Admin\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\Transaksi;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class Pendapatan extends ChartWidget
{
    protected static ?string $heading = '💰 Grafik Pendapatan Bulanan';
    
    protected static ?string $description = 'Analisis performa pendapatan dengan indikator visual dinamis';
    
    protected static ?int $sort = 2;
    
    protected int | string | array $columnSpan = 'full';
    
    protected static ?string $maxHeight = '400px';

    protected function getData(): array
    {
        $currentYear = now()->year;
        
        // Query data dengan informasi lebih lengkap
        $data = Transaksi::selectRaw('
                MONTH(tanggal) as bulan,
                YEAR(tanggal) as tahun,
                SUM(total) as total,
                COUNT(*) as jumlah_transaksi,
                AVG(total) as rata_rata_transaksi
            ')
            ->where('status_pembayaran', 'lunas')
            ->whereYear('tanggal', $currentYear)
            ->groupBy(DB::raw('YEAR(tanggal), MONTH(tanggal)'))
            ->orderBy('tahun')
            ->orderBy('bulan')
            ->get();

        if ($data->isEmpty()) {
            return [
                'datasets' => [
                    [
                        'label' => 'Tidak ada data',
                        'data' => [],
                        'backgroundColor' => ['rgba(156, 163, 175, 0.5)'],
                    ]
                ],
                'labels' => ['Tidak ada data'],
            ];
        }

        // Siapkan data untuk semua bulan (1-12)
        $monthlyData = collect(range(1, 12))->map(function ($month) use ($data) {
            $monthData = $data->where('bulan', $month)->first();
            return [
                'bulan' => $month,
                'total' => $monthData ? $monthData->total : 0,
                'jumlah_transaksi' => $monthData ? $monthData->jumlah_transaksi : 0,
                'rata_rata_transaksi' => $monthData ? $monthData->rata_rata_transaksi : 0,
            ];
        });

        // Hitung statistik
        $nonZeroData = $monthlyData->where('total', '>', 0);
        $average = $nonZeroData->avg('total') ?: 0;
        $maxValue = $monthlyData->max('total');
        $minValue = $nonZeroData->min('total') ?: 0;
        
        // Buat label yang sudah include jumlah transaksi
        $bulanLabels = [];
        $totals = [];
        $backgroundColors = [];
        $borderColors = [];
        $hoverColors = [];
        $tooltipLabels = []; // Array untuk tooltip custom

        // Nama bulan dalam bahasa Indonesia
        $namabulan = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];

        foreach ($monthlyData as $row) {
            $bulanLabels[] = $namabulan[$row['bulan']];
            $totals[] = $row['total'];
            $tooltipLabels[] = [
                'bulan' => $namabulan[$row['bulan']],
                'pendapatan' => 'Rp ' . number_format($row['total'], 0, ',', '.'),
                'transaksi' => $row['jumlah_transaksi'] . ' transaksi'
            ];

            // Sistem warna yang lebih sophisticated
            if ($row['total'] == 0) {
                // Tidak ada data
                $backgroundColors[] = 'rgba(156, 163, 175, 0.3)';
                $borderColors[] = 'rgba(156, 163, 175, 0.8)';
                $hoverColors[] = 'rgba(156, 163, 175, 0.5)';
            } elseif ($row['total'] == $maxValue && $maxValue > 0) {
                // Performa terbaik - Gold gradient
                $backgroundColors[] = 'linear-gradient(135deg, rgba(255, 215, 0, 0.8), rgba(255, 193, 7, 0.9))';
                $borderColors[] = 'rgba(255, 193, 7, 1)';
                $hoverColors[] = 'rgba(255, 215, 0, 1)';
            } elseif ($row['total'] >= $average * 1.3) {
                // Sangat baik - Green gradient
                $backgroundColors[] = 'linear-gradient(135deg, rgba(34, 197, 94, 0.8), rgba(22, 163, 74, 0.9))';
                $borderColors[] = 'rgba(22, 163, 74, 1)';
                $hoverColors[] = 'rgba(34, 197, 94, 1)';
            } elseif ($row['total'] >= $average * 1.1) {
                // Baik - Blue gradient
                $backgroundColors[] = 'linear-gradient(135deg, rgba(59, 130, 246, 0.8), rgba(37, 99, 235, 0.9))';
                $borderColors[] = 'rgba(37, 99, 235, 1)';
                $hoverColors[] = 'rgba(59, 130, 246, 1)';
            } elseif ($row['total'] >= $average * 0.8) {
                // Rata-rata - Orange gradient
                $backgroundColors[] = 'linear-gradient(135deg, rgba(251, 146, 60, 0.8), rgba(249, 115, 22, 0.9))';
                $borderColors[] = 'rgba(249, 115, 22, 1)';
                $hoverColors[] = 'rgba(251, 146, 60, 1)';
            } else {
                // Perlu perbaikan - Red gradient
                $backgroundColors[] = 'linear-gradient(135deg, rgba(239, 68, 68, 0.8), rgba(220, 38, 38, 0.9))';
                $borderColors[] = 'rgba(220, 38, 38, 1)';
                $hoverColors[] = 'rgba(239, 68, 68, 1)';
            }
        }

        // Dataset utama dengan informasi tooltip
        $mainDataset = [
            'label' => '💰 Pendapatan',
            'data' => $totals,
            'backgroundColor' => $backgroundColors,
            'borderColor' => $borderColors,
            'hoverBackgroundColor' => $hoverColors,
            'borderWidth' => 2,
            'borderRadius' => 6,
            'hoverBorderWidth' => 3,
            'tooltipLabels' => $tooltipLabels, // Data untuk tooltip
        ];

        // Dataset tersembunyi untuk menampilkan jumlah transaksi di tooltip
        $hiddenDataset = [
            'label' => '🧾 Jumlah Transaksi',
            'data' => array_column($monthlyData->toArray(), 'jumlah_transaksi'),
            'backgroundColor' => 'rgba(0,0,0,0)',
            'borderColor' => 'rgba(0,0,0,0)',
            'borderWidth' => 0,
            'pointRadius' => 0,
            'pointHoverRadius' => 0,
            'type' => 'line',
            'showLine' => false,
        ];

        // Garis rata-rata sebagai referensi
        $averageLine = [
            'label' => '📊 Rata-rata',
            'data' => array_fill(0, 12, $average),
            'type' => 'line',
            'borderColor' => 'rgba(139, 69, 19, 0.8)',
            'backgroundColor' => 'rgba(139, 69, 19, 0.1)',
            'borderWidth' => 3,
            'borderDash' => [10, 5],
            'fill' => false,
            'pointRadius' => 0,
            'pointHoverRadius' => 6,
            'pointBackgroundColor' => 'rgba(139, 69, 19, 1)',
            'pointBorderColor' => '#ffffff',
            'pointBorderWidth' => 2,
        ];

        return [
            'datasets' => [$mainDataset, $averageLine, $hiddenDataset],
            'labels' => $bulanLabels,
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }

    protected function getOptions(): array
    {
        return [
            'responsive' => true,
            'maintainAspectRatio' => false,
            'plugins' => [
                'legend' => [
                    'display' => true,
                    'position' => 'top',
                    'labels' => [
                        'usePointStyle' => true,
                        'padding' => 20,
                        'font' => [
                            'size' => 12,
                            'weight' => 'bold',
                        ],
                    ],
                ],
                'tooltip' => [
                    'backgroundColor' => 'rgba(0, 0, 0, 0.8)',
                    'titleColor' => '#fff',
                    'bodyColor' => '#fff',
                    'borderColor' => 'rgba(255, 255, 255, 0.1)',
                    'borderWidth' => 1,
                    'cornerRadius' => 8,
                    'padding' => 12,
                    'mode' => 'index',
                    'intersect' => false,
                    'displayColors' => false,
                ],
            ],
            'scales' => [
                'x' => [
                    'display' => true,
                    'title' => [
                        'display' => true,
                        'text' => '📅 Bulan',
                        'font' => [
                            'size' => 14,
                            'weight' => 'bold',
                        ],
                        'color' => 'rgba(55, 65, 81, 0.8)',
                    ],
                    'grid' => [
                        'display' => false,
                    ],
                    'ticks' => [
                        'font' => [
                            'size' => 12,
                            'weight' => '500',
                        ],
                        'color' => 'rgba(75, 85, 99, 0.8)',
                    ],
                ],
                'y' => [
                    'display' => true,
                    'title' => [
                        'display' => true,
                        'text' => '💰 Pendapatan (Rupiah)',
                        'font' => [
                            'size' => 14,
                            'weight' => 'bold',
                        ],
                        'color' => 'rgba(55, 65, 81, 0.8)',
                    ],
                    'beginAtZero' => true,
                    'grid' => [
                        'color' => 'rgba(156, 163, 175, 0.2)',
                        'drawBorder' => false,
                    ],
                    'ticks' => [
                        'font' => [
                            'size' => 11,
                        ],
                        'color' => 'rgba(75, 85, 99, 0.8)',
                    ],
                ],
            ],
            'animation' => [
                'duration' => 1000,
            ],
            'elements' => [
                'bar' => [
                    'borderRadius' => 6,
                ],
            ],
        ];
    }

    public function getDescription(): ?string
    {
        $currentYear = now()->year;
        $totalPendapatan = Transaksi::where('status_pembayaran', 'lunas')
            ->whereYear('tanggal', $currentYear)
            ->sum('total');
        
        $totalTransaksi = Transaksi::where('status_pembayaran', 'lunas')
            ->whereYear('tanggal', $currentYear)
            ->count();

        $formattedTotal = 'Rp ' . number_format($totalPendapatan, 0, ',', '.');
        
        return "📈 Total Pendapatan {$currentYear}: {$formattedTotal} | 🧾 Total Transaksi: {$totalTransaksi}";
    }

    public static function canView(): bool
{
    return auth()->user()->hasRole('owner');
}

}