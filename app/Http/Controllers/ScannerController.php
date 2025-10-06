<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\QrCodeService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ScannerController extends Controller
{
    public function __construct(
        private QrCodeService $qrCodeService
    ) {}

    /**
     * Display the QR scanner page
     */
    public function index(): Response
    {
        return Inertia::render('Admin/Scanner');
    }

    /**
     * Process QR code scan from admin scanner
     */
    public function scan(Request $request): JsonResponse
    {
        $request->validate([
            'qr_data' => 'required|string',
        ]);

        $result = $this->qrCodeService->processQrScan(
            $request->qr_data,
            auth()->user()->name ?? 'Admin'
        );

        $statusCode = $result['success'] ? 200 : 400;

        return response()->json($result, $statusCode);
    }

    /**
     * Display scan result page
     */
    public function scanResult(Request $request)
    {
        // Handle POST request to store result in session
        if ($request->isMethod('post')) {
            $result = $request->input('result');
            $request->session()->put('scan_result', $result);
            return redirect()->route('admin.scan-result');
        }

        // Handle GET request to display result
        $result = $request->session()->get('scan_result', [
            'success' => false,
            'message' => 'No scan result found',
        ]);

        return Inertia::render('Admin/ScanResult', [
            'result' => $result,
        ]);
    }

    /**
     * Display today's check-ins
     */
    public function checkIns()
    {
        $user = auth()->user();

        // Get today's check-ins for user's events
        $checkIns = \App\Models\Attendance::whereHas('event', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })
        ->whereNotNull('used_at')
        ->whereDate('used_at', today())
        ->with('event')
        ->orderBy('used_at', 'desc')
        ->limit(50)
        ->get()
        ->map(function ($attendance) {
            return [
                'id' => $attendance->id,
                'attendee_name' => $attendance->attendee_name,
                'event_title' => $attendance->event->title,
                'checked_in_by' => $attendance->checked_in_by,
                'used_at' => $attendance->used_at->toISOString(),
            ];
        });

        // Calculate statistics
        $totalCheckIns = \App\Models\Attendance::whereHas('event', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })
        ->whereNotNull('used_at')
        ->whereDate('used_at', today())
        ->count();

        $uniqueEvents = \App\Models\Attendance::whereHas('event', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })
        ->whereNotNull('used_at')
        ->whereDate('used_at', today())
        ->distinct('event_id')
        ->count();

        $thisHour = \App\Models\Attendance::whereHas('event', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })
        ->whereNotNull('used_at')
        ->whereDate('used_at', today())
        ->where('used_at', '>=', now()->startOfHour())
        ->count();

        $last30Minutes = \App\Models\Attendance::whereHas('event', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })
        ->whereNotNull('used_at')
        ->where('used_at', '>=', now()->subMinutes(30))
        ->count();

        return Inertia::render('Admin/CheckIns', [
            'checkIns' => $checkIns,
            'stats' => [
                'totalCheckIns' => $totalCheckIns,
                'uniqueEvents' => $uniqueEvents,
                'thisHour' => $thisHour,
                'last30Minutes' => $last30Minutes,
            ],
            'hasMore' => false, // For now, we'll show all results
        ]);
    }
}
