<?php

namespace App\Filament\Widgets;

use App\Models\Event;
use App\Models\Attendance;
use Filament\Widgets\ChartWidget;

class EventsBarChartWidget extends ChartWidget
{
    protected ?string $heading = 'إحصائيات الأحداث';

    protected ?string $description = 'عدد المسجلين لكل حدث';

    protected int | string | array $columnSpan = 'full';

    protected function getData(): array
    {
        $events = Event::query()
            ->withCount('attendances')
            ->orderBy('attendances_count', 'desc')
            ->limit(10)
            ->get();

        return [
            'datasets' => [
                [
                    'label' => 'عدد المسجلين',
                    'data' => $events->pluck('attendances_count'),
                    'backgroundColor' => [
                        '#3B82F6', '#10B981', '#F59E0B', '#EF4444', '#8B5CF6',
                        '#06B6D4', '#84CC16', '#F97316', '#EC4899', '#6B7280'
                    ],
                    'borderColor' => [
                        '#1D4ED8', '#059669', '#D97706', '#DC2626', '#7C3AED',
                        '#0891B2', '#65A30D', '#EA580C', '#DB2777', '#4B5563'
                    ],
                    'borderWidth' => 2,
                ],
            ],
            'labels' => $events->pluck('title'),
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
