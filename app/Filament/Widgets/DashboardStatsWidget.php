<?php

namespace App\Filament\Widgets;

use App\Models\Event;
use App\Models\Attendance;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class DashboardStatsWidget extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        $totalEvents = Event::count();
        $totalAttendees = Attendance::count();
        $totalCheckIns = Attendance::whereNotNull('used_at')->count();
        $totalUsers = User::count();

        return [
            Stat::make('إجمالي الأحداث', $totalEvents)
                ->description('جميع الأحداث المسجلة')
                ->descriptionIcon('heroicon-m-calendar-days')
                ->color('primary'),

            Stat::make('إجمالي المسجلين', $totalAttendees)
                ->description('جميع التسجيلات')
                ->descriptionIcon('heroicon-m-users')
                ->color('success'),

            Stat::make('الحضور المؤكد', $totalCheckIns)
                ->description('تم تسجيل الحضور')
                ->descriptionIcon('heroicon-m-check-circle')
                ->color('warning'),

            Stat::make('إجمالي المستخدمين', $totalUsers)
                ->description('المستخدمون المسجلون')
                ->descriptionIcon('heroicon-m-user-group')
                ->color('info'),
        ];
    }
}
