<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\AttendanceRecordsWidget;
use App\Filament\Widgets\AttendanceStatsWidget;
use App\Filament\Widgets\AvailableQrCodesWidget;
use BackedEnum;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;
use Illuminate\Contracts\Support\Htmlable;

class MyAttendance extends Page
{
    protected string $view = 'filament.pages.my-attendance';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::CalendarDays;

    protected static ?string $navigationLabel = 'My Attendance';

    protected static ?string $title = 'My Attendance';

    protected static ?string $slug = 'my-attendance';

    protected static ?int $navigationSort = 3;

    public function getTitle(): string|Htmlable
    {
        return 'My Attendance';
    }

    protected function getHeaderWidgets(): array
    {
        return [
            AttendanceStatsWidget::class,
        ];
    }

    protected function getFooterWidgets(): array
    {
        return [
            AvailableQrCodesWidget::class,
            AttendanceRecordsWidget::class,
        ];
    }
}
