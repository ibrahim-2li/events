<?php

namespace App\Filament\Widgets;

use App\Models\Event;
use App\Models\Attendance;
use Filament\Widgets\ChartWidget;
use Carbon\Carbon;

class CheckInsLineChartWidget extends ChartWidget
{
    protected ?string $heading = 'اتجاه الحضور';

    protected ?string $description = 'تطور عدد الحضور المؤكد عبر الوقت';

    protected int | string | array $columnSpan = 'half';

    protected function getData(): array
    {
        $data = Attendance::query()
            ->whereNotNull('used_at')
            ->selectRaw('DATE(used_at) as date, COUNT(*) as count')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Fill missing dates with 0
        $last30Days = collect();
        for ($i = 29; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i)->format('Y-m-d');
            $count = $data->where('date', $date)->first()?->count ?? 0;
            $last30Days->push([
                'date' => $date,
                'count' => $count
            ]);
        }

        return [
            'datasets' => [
                [
                    'label' => 'عدد الحضور',
                    'data' => $last30Days->pluck('count'),
                    'borderColor' => '#3B82F6',
                    'backgroundColor' => 'rgba(59, 130, 246, 0.1)',
                    'fill' => true,
                    'tension' => 0.4,
                ],
            ],
            'labels' => $last30Days->pluck('date')->map(function ($date) {
                return Carbon::parse($date)->format('M d');
            }),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
