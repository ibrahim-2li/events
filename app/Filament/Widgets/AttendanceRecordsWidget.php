<?php

namespace App\Filament\Widgets;

use Filament\Tables;
use App\Models\Attendance;
use Filament\Tables\Table;
use Tables\Actions\BulkAction;
use Filament\Widgets\TableWidget as BaseWidget;

class AttendanceRecordsWidget extends BaseWidget
{
    protected int | string | array $columnSpan = 'full';

    protected static bool $isDiscovered = false;

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Attendance::query()
                    ->where('attendee_email', auth()->user()->email)
                    ->with('event')
                    ->orderBy('created_at', 'desc')
            )
            ->columns([
                Tables\Columns\TextColumn::make('event.title')
                    ->label('اسم الحدث')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('event.start_date')
                    ->label('تاريخ الحدث')
                    ->date('Y-m-d')
                    ->sortable(),

                Tables\Columns\TextColumn::make('used_at')
                    ->label('وقت التسجيل')
                    ->dateTime('Y-m-d H:i')
                    ->placeholder('لم يتم التسجيل')
                    ->sortable(),

                Tables\Columns\TextColumn::make('checked_in_by')
                    ->label('سجل بواسطة')
                    ->placeholder('لم يتم التسجيل')
                    ->searchable(),

                Tables\Columns\TextColumn::make('status')
                    ->label('الحالة')
                    ->getStateUsing(function (Attendance $record): string {
                        return $record->used_at ? 'تم الحضور' : 'في الانتظار';
                    })
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'تم الحضور' => 'success',
                        'في الانتظار' => 'warning',
                        default => 'gray',
                    }),
            ])
            ->filters([
                Tables\Filters\Filter::make('checked_in')
                    ->label('تم الحضور')
                    ->query(fn ($query) => $query->whereNotNull('used_at')),

                Tables\Filters\Filter::make('pending')
                    ->label('في الانتظار')
                    ->query(fn ($query) => $query->whereNull('used_at')),
            ])
            ->actions([
                // No actions needed for this widget
            ])
            ->emptyStateHeading('لا توجد سجلات حضور')
            ->emptyStateDescription('لم يتم العثور على أي سجلات حضور.')
            ->emptyStateIcon('heroicon-o-calendar-days');
    }
}
