<?php

namespace App\Filament\Pages;

use BackedEnum;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Facades\Auth;
use App\Filament\Widgets\RecentEventsWidget;
use App\Filament\Widgets\DashboardStatsWidget;
use App\Filament\Widgets\EventsBarChartWidget;
use App\Filament\Widgets\CheckInsLineChartWidget;
use App\Filament\Widgets\AttendancesPieChartWidget;

class CustomDashboard extends Page
{
    protected string $view = 'filament.pages.custom-dashboard';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::QrCode;

    public static function canAccess(): bool
    {
        return Auth::check() && Auth::user()->isAdmin() || Auth::user()->isScanner();
    }


    protected static ?string $navigationLabel = 'QR CodeScanner';

    protected static ?string $title = 'QR CodeScanner';

    protected static ?int $navigationSort = 1;

    protected function getHeaderWidgets(): array
    {
        return [
            // DashboardStatsWidget::class,
        ];
    }

    protected function getFooterWidgets(): array
    {
        return [
            // EventsBarChartWidget::class,
            // AttendancesPieChartWidget::class,
            // CheckInsLineChartWidget::class,
            // RecentEventsWidget::class,
        ];
    }
}
