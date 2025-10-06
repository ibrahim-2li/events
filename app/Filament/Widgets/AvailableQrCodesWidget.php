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

    protected static bool $isDiscovered = false;

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

                Tables\Columns\TextColumn::make('created_at')
                    ->label('تاريخ التسجيل')
                    ->dateTime('Y-m-d H:i')
                    ->sortable(),

                Tables\Columns\TextColumn::make('actions')
                    ->label('الإجراءات')
                    ->getStateUsing(function (Attendance $record): string {
                        $viewUrl = route('view-qr', $record->id);
                        $downloadUrl = route('download-qr', $record->id);

                        return "
                            <div class='flex gap-2'>
                                <a href='{$viewUrl}' target='_blank' class='inline-flex items-center px-3 py-1 text-sm font-medium text-blue-600 bg-blue-100 rounded-md hover:bg-blue-200'>
                                    <svg class='w-4 h-4 mr-1' fill='none' stroke='currentColor' viewBox='0 0 24 24'>
                                        <path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M15 12a3 3 0 11-6 0 3 3 0 016 0z'></path>
                                        <path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z'></path>
                                    </svg>
                                    عرض
                                </a>
                                <a href='{$downloadUrl}' class='inline-flex items-center px-3 py-1 text-sm font-medium text-green-600 bg-green-100 rounded-md hover:bg-green-200'>
                                    <svg class='w-4 h-4 mr-1' fill='none' stroke='currentColor' viewBox='0 0 24 24'>
                                        <path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z'></path>
                                    </svg>
                                    تحميل SVG
                                </a>
                            </div>
                        ";
                    })
                    ->html(),
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
                // Using clickable rows instead
            ])
            ->emptyStateHeading('لا توجد رموز QR متاحة')
            ->emptyStateDescription('جميع رموز QR الخاصة بك تم استخدامها أو لا توجد رموز متاحة.')
            ->emptyStateIcon('heroicon-o-qr-code')
            ->heading('رموز QR المتاحة')
            ->description('رموز QR التي لم يتم استخدامها بعد');
    }
}
