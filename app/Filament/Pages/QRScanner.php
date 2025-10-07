<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use BackedEnum;
use Filament\Support\Icons\Heroicon;

class QRScanner extends Page
{
    protected string $view = 'filament.pages.q-r-scanner';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::QrCode;

    protected static ?string $navigationLabel = 'ماسح QR';

    protected static ?int $navigationSort = 2;

    protected static bool $shouldRegisterNavigation = false; // Hide from navigation, access via dashboard button

    public function mount(): void
    {
        // Redirect to the scanner page
        $this->redirect(route('scanner'));
    }
}
