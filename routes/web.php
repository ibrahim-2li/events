<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EventController;

Route::get('/', [EventController::class, 'index'])->name('events.index');
Route::get('/events/{event}', [EventController::class, 'show'])->name('events.show');
Route::post('/events/{event}/register', [EventController::class, 'register'])->name('events.register');

// QR Code Scanner routes - Admin and Scanner only
Route::middleware(['auth', 'scanner.access'])->group(function () {
    Route::get('/scanner', function () {
        return view('scanner');
    })->name('scanner');

    Route::get('/test-scanner', function () {
        return view('test-scanner');
    })->name('test-scanner');

    Route::post('/api/validate-qr', [EventController::class, 'validateQrCode'])->name('api.validate-qr');
});
