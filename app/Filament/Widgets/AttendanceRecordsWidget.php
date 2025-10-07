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
                    ->label('Event Name')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('event.start_date')
                    ->label('Event Date')
                    ->date('Y-m-d')
                    ->sortable(),

                Tables\Columns\TextColumn::make('used_at')
                    ->label('Registration Date')
                    ->dateTime('Y-m-d H:i')
                    ->placeholder('Not registered')
                    ->sortable(),

                Tables\Columns\TextColumn::make('checked_in_by')
                    ->label('Checked In By')
                    ->placeholder('Not registered')
                    ->searchable(),

                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->getStateUsing(function (Attendance $record): string {
                        return $record->used_at ? 'Checked In' : 'Pending';
                    })
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Checked In' => 'success',
                        'Pending' => 'warning',
                        default => 'gray',
                    }),
            ])
            ->filters([
                Tables\Filters\Filter::make('checked_in')
                    ->label('Checked In')
                    ->query(fn ($query) => $query->whereNotNull('used_at')),

                Tables\Filters\Filter::make('pending')
                    ->label('Pending')
                    ->query(fn ($query) => $query->whereNull('used_at')),
            ])
            ->actions([
                // No actions needed for this widget
            ])
            ->emptyStateHeading('No Attendance Records')
            ->emptyStateDescription('No attendance records found.')
            ->emptyStateIcon('heroicon-o-calendar-days');
    }
}
