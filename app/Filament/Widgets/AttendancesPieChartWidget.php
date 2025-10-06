<?php

namespace App\Filament\Widgets;

use App\Models\Event;
use App\Models\Attendance;
use Filament\Widgets\ChartWidget;

class AttendancesPieChartWidget extends ChartWidget
{
    protected ?string $heading = 'توزيع الحضور';

    protected ?string $description = 'نسبة الحضور المؤكد مقابل المعلق';

    protected int | string | array $columnSpan = 'half';

    protected function getData(): array
    {
        $checkedIn = Attendance::whereNotNull('used_at')->count();
        $pending = Attendance::whereNull('used_at')->count();

        return [
            'datasets' => [
                [
                    'label' => 'الحضور',
                    'data' => [$checkedIn, $pending],
                    'backgroundColor' => [
                        '#10B981', // Green for checked in
                        '#F59E0B', // Yellow for pending
                    ],
                    'borderColor' => [
                        '#059669',
                        '#D97706',
                    ],
                    'borderWidth' => 0,
                ],
            ],
            'labels' => ['تم الحضور', 'في الانتظار'],
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }
}
