<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Inertia\Response;
use App\Models\Attendance;
use App\Services\QrCodeService;
use App\Http\Controllers\Controller;

class UserController extends Controller
{
    public function __construct(
        private QrCodeService $qrCodeService
    ) {}

    /**
     * Display user dashboard
     */
    public function dashboard(): Response
    {
        $user = auth()->user();

        // Get user's attendance statistics
        $totalEventsAttended = Attendance::where('attendee_email', $user->email)
            ->whereNull('used_at')
            ->count();

        $thisMonth = Attendance::where('attendee_email', $user->email)
            ->whereNotNull('used_at')
            ->whereMonth('used_at', now()->month)
            ->whereYear('used_at', now()->year)
            ->count();

        $recentCheckIns = Attendance::where('attendee_email', $user->email)
            ->whereNotNull('used_at')
            ->where('used_at', '>=', now()->subDays(7))
            ->count();

        // Get recent attendances
        $recentAttendances = Attendance::where('attendee_email', $user->email)
            ->whereNotNull('used_at')
            ->with('event')
            ->orderBy('used_at', 'desc')
            ->limit(5)
            ->get()
            ->map(function ($attendance) {
                return [
                    'id' => $attendance->id,
                    'event_title' => $attendance->event->title,
                    'used_at' => $attendance->used_at->toISOString(),
                ];
            });

        // Get user's QR codes (attendances)
        $qrCodes = Attendance::where('attendee_email', $user->email)
            ->with('event')
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($attendance) {
                // Generate QR code URL if not already generated
                $qrCodeUrl = null;
                if (! $attendance->used_at) {
                    try {
                        $qrCodeUrl = app(\App\Services\QrCodeService::class)->generateAttendanceQrCode($attendance);
                    } catch (\Exception $e) {
                        // If QR code generation fails, continue without it
                        $qrCodeUrl = null;
                    }
                }

                return [
                    'id' => $attendance->id,
                    'event_title' => $attendance->event->title,
                    'event_date' => $attendance->event->start_date->toDateString(),
                    'qr_code_url' => $qrCodeUrl,
                    'used_at' => $attendance->used_at?->toISOString(),
                ];
            });

        // Calculate QR codes statistics
        $totalQrCodes = $qrCodes->count();
        $usedCodes = $qrCodes->where('used_at', '!=', null)->count();
        $availableCodes = $totalQrCodes - $usedCodes;

        return Inertia::render('User/Dashboard', [
            'user' => $user,
            'stats' => [
                'totalEventsAttended' => $totalEventsAttended,
                'thisMonth' => $thisMonth,
                'recentCheckIns' => $recentCheckIns,
            ],
            'recentAttendances' => $recentAttendances,
            'qrCodes' => $qrCodes,
            'qrStats' => [
                'totalQrCodes' => $totalQrCodes,
                'usedCodes' => $usedCodes,
                'availableCodes' => $availableCodes,
            ],
            'currentLocale' => app()->getLocale(),
            'translations' => [
                'common' => __('common'),
            ],
        ]);
    }

    /**
     * Display user attendances
     */
    public function attendances(): Response
    {
        $user = auth()->user();

        // Get user's attendances
        $attendances = Attendance::where('attendee_email', $user->email)
            ->whereNotNull('used_at')
            ->with('event')
            ->orderBy('used_at', 'desc')
            ->get()
            ->map(function ($attendance) {
                return [
                    'id' => $attendance->id,
                    'event_title' => $attendance->event->title,
                    'event_date' => $attendance->event->start_date->toDateString(),
                    'used_at' => $attendance->used_at->toISOString(),
                ];
            });

        // Calculate statistics
        $totalAttendances = $attendances->count();

        $thisMonth = Attendance::where('attendee_email', $user->email)
            ->whereNotNull('used_at')
            ->whereMonth('used_at', now()->month)
            ->whereYear('used_at', now()->year)
            ->count();

        $thisWeek = Attendance::where('attendee_email', $user->email)
            ->whereNotNull('used_at')
            ->where('used_at', '>=', now()->startOfWeek())
            ->count();

        $today = Attendance::where('attendee_email', $user->email)
            ->whereNotNull('used_at')
            ->whereDate('used_at', today())
            ->count();

        return Inertia::render('User/Attendances', [
            'attendances' => $attendances,
            'stats' => [
                'totalAttendances' => $totalAttendances,
                'thisMonth' => $thisMonth,
                'thisWeek' => $thisWeek,
                'today' => $today,
            ],
            'user' => $user,
            'currentLocale' => app()->getLocale(),
            'translations' => [
                'common' => __('common'),
            ],
        ]);
    }

    /**
     * Display user QR codes
     */
    public function qrCodes(): Response
    {
        $user = auth()->user();

        // Get user's QR codes (attendances)
        $qrCodes = Attendance::where('attendee_email', $user->email)
            ->with('event')
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($attendance) {
                // Generate QR code URL if not already generated
                $qrCodeUrl = null;
                if (! $attendance->used_at) {
                    try {
                        $qrCodeUrl = $this->qrCodeService->generateAttendanceQrCode($attendance);
                    } catch (\Exception $e) {
                        // If QR code generation fails, continue without it
                        $qrCodeUrl = null;
                    }
                }

                return [
                    'id' => $attendance->id,
                    'event_title' => $attendance->event->title,
                    'event_date' => $attendance->event->start_date->toDateString(),
                    'qr_code_url' => $qrCodeUrl,
                    'used_at' => $attendance->used_at?->toISOString(),
                ];
            });

        // Calculate statistics
        $totalQrCodes = $qrCodes->count();
        $usedCodes = $qrCodes->where('used_at', '!=', null)->count();
        $availableCodes = $totalQrCodes - $usedCodes;

        return Inertia::render('User/QrCodes', [
            'qrCodes' => $qrCodes,
            'stats' => [
                'totalQrCodes' => $totalQrCodes,
                'usedCodes' => $usedCodes,
                'availableCodes' => $availableCodes,
            ],
            'user' => $user,
            'currentLocale' => app()->getLocale(),
            'translations' => [
                'common' => __('common'),
            ],
        ]);
    }
}
