<?php

namespace App\Filament\Widgets;

use App\Models\Attendance;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;



class AttendanceStatsWidget extends StatsOverviewWidget
{
    protected static bool $isDiscovered = false;

    protected function getStats(): array
    {
        $userEmail = auth()->user()->email;

        $totalAttendances = Attendance::where('attendee_email', $userEmail)->count();

        $thisMonthAttendances = Attendance::where('attendee_email', $userEmail)
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();

        $thisWeekAttendances = Attendance::where('attendee_email', $userEmail)
            ->whereBetween('created_at', [
                now()->startOfWeek(),
                now()->endOfWeek()
            ])
            ->count();

        $todayAttendances = Attendance::where('attendee_email', $userEmail)
            ->whereDate('created_at', today())
            ->count();

        return [
            Stat::make('إجمالي الحضور', $totalAttendances)
                ->description('جميع سجلات الحضور')
                ->descriptionIcon('heroicon-m-users')
                ->color('primary'),

            Stat::make('هذا الشهر', $thisMonthAttendances)
                ->description('حضور هذا الشهر')
                ->descriptionIcon('heroicon-m-calendar-days')
                ->color('success'),

            Stat::make('هذا الأسبوع', $thisWeekAttendances)
                ->description('حضور هذا الأسبوع')
                ->descriptionIcon('heroicon-m-chart-bar')
                ->color('warning'),

            Stat::make('اليوم', $todayAttendances)
                ->description('حضور اليوم')
                ->descriptionIcon('heroicon-m-clock')
                ->color('info'),
        ];
    }
}
