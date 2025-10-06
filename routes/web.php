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

// QR Code download route - Authenticated users only
Route::middleware(['auth'])->group(function () {
    Route::get('/download-qr/{attendance}', function (App\Models\Attendance $attendance) {
        // Check if user owns this attendance record
        if ($attendance->attendee_email !== auth()->user()->email) {
            abort(403, 'ليس لديك صلاحية لتحميل هذا الرمز');
        }

        $qrData = json_encode([
            'type' => 'attendance',
            'event_id' => $attendance->event_id,
            'token' => $attendance->qr_token,
            'attendee_name' => $attendance->attendee_name,
            'attendee_email' => $attendance->attendee_email,
        ]);

        $qrCode = \SimpleSoftwareIO\QrCode\Facades\QrCode::size(400)
            ->format('svg')
            ->generate($qrData);

        $filename = 'qr-code-' . \Str::slug($attendance->event->title) . '-' . $attendance->qr_token . '.svg';

        return response($qrCode)
            ->header('Content-Type', 'image/svg+xml')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
    })->name('download-qr');

    Route::get('/view-qr/{attendance}', function (App\Models\Attendance $attendance) {
        // Check if user owns this attendance record
        if ($attendance->attendee_email !== auth()->user()->email) {
            abort(403, 'ليس لديك صلاحية لعرض هذا الرمز');
        }

        $qrData = json_encode([
            'type' => 'attendance',
            'event_id' => $attendance->event_id,
            'token' => $attendance->qr_token,
            'attendee_name' => $attendance->attendee_name,
            'attendee_email' => $attendance->attendee_email,
        ]);

        $qrCode = \SimpleSoftwareIO\QrCode\Facades\QrCode::size(300)
            ->format('svg')
            ->generate($qrData);

        return view('qr-code-view', [
            'qrCode' => $qrCode,
            'attendance' => $attendance,
            'event' => $attendance->event
        ]);
    })->name('view-qr');
});
