<?php

namespace App\Filament\Widgets;

use App\Models\Attendance;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class AvailableQrCodesWidget extends BaseWidget
{
    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Attendance::query()
                    ->where('attendee_email', auth()->user()->email)
                    ->whereNull('used_at') // Only show unused QR codes
                    ->with('event')
                    ->orderBy('created_at', 'desc')
            )
            ->columns([
                Tables\Columns\TextColumn::make('event.title')
                    ->label('اسم الحدث')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                Tables\Columns\TextColumn::make('event.start_date')
                    ->label('تاريخ الحدث')
                    ->date('Y-m-d')
                    ->sortable(),

                Tables\Columns\TextColumn::make('event.location')
                    ->label('مكان الحدث')
                    ->placeholder('غير محدد')
                    ->searchable(),

                Tables\Columns\TextColumn::make('qr_token')
                    ->label('رمز QR')
                    ->copyable()
                    ->copyMessage('تم نسخ الرمز!')
                    ->searchable()
                    ->limit(20),

                Tables\Columns\TextColumn::make('qr_code_display')
                    ->label('رمز QR')
                    ->getStateUsing(function (Attendance $record): string {
                        $qrData = json_encode([
                            'type' => 'attendance',
                            'event_id' => $record->event_id,
                            'token' => $record->qr_token,
                            'attendee_name' => $record->attendee_name,
                            'attendee_email' => $record->attendee_email,
                        ]);

                        $qrCode = QrCode::size(150)
                            ->format('svg')
                            ->generate($qrData);

                        return $qrCode;
                    })
                    ->html()
                    ->wrap(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('تاريخ التسجيل')
                    ->dateTime('Y-m-d H:i')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('event_id')
                    ->label('الحدث')
                    ->relationship('event', 'title')
                    ->searchable()
                    ->preload(),

                Tables\Filters\Filter::make('recent')
                    ->label('الحديثة')
                    ->query(fn ($query) => $query->where('created_at', '>=', now()->subDays(7))),
            ])
            ->actions([
                // Actions removed due to compatibility issues
                // QR codes are displayed as images in the table
            ])
            ->emptyStateHeading('لا توجد رموز QR متاحة')
            ->emptyStateDescription('جميع رموز QR الخاصة بك تم استخدامها أو لا توجد رموز متاحة.')
            ->emptyStateIcon('heroicon-o-qr-code')
            ->heading('رموز QR المتاحة')
            ->description('رموز QR التي لم يتم استخدامها بعد');
    }
}
