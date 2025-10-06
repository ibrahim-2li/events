<?php

namespace App\Filament\Widgets;

use App\Models\Event;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class RecentEventsWidget extends BaseWidget
{
    protected static ?string $heading = 'الأحداث الأخيرة';

    protected static bool $isDiscovered = false;

    protected int | string | array $columnSpan = 'full';

    protected function getTableQuery(): \Illuminate\Database\Eloquent\Builder
    {
        return Event::query()
            ->withCount('attendances')
            ->withCount('checkedInAttendances')
            ->orderBy('created_at', 'desc')
            ->limit(5);
    }

    protected function getTableColumns(): array
    {
        return [
            Tables\Columns\TextColumn::make('title')
                ->label('اسم الحدث')
                ->searchable()
                ->sortable()
                ->limit(30),

            Tables\Columns\TextColumn::make('start_date')
                ->label('تاريخ البداية')
                ->dateTime('Y-m-d H:i')
                ->sortable(),

            Tables\Columns\TextColumn::make('location')
                ->label('المكان')
                ->placeholder('غير محدد')
                ->searchable(),

            Tables\Columns\TextColumn::make('attendances_count')
                ->label('إجمالي المسجلين')
                ->numeric()
                ->sortable(),

            Tables\Columns\TextColumn::make('checked_in_attendances_count')
                ->label('الحضور المؤكد')
                ->numeric()
                ->sortable(),

            Tables\Columns\IconColumn::make('is_active')
                ->label('نشط')
                ->boolean()
                ->sortable(),
        ];
    }

    protected function getTableActions(): array
    {
        return [
            // Actions removed due to compatibility issues
        ];
    }

    protected function getTableEmptyStateHeading(): ?string
    {
        return 'لا توجد أحداث';
    }

    protected function getTableEmptyStateDescription(): ?string
    {
        return 'لم يتم إنشاء أي أحداث بعد.';
    }

    protected function getTableEmptyStateIcon(): ?string
    {
        return 'heroicon-o-calendar-days';
    }
}
