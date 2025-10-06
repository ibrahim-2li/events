<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Attendance;
use App\Mail\AttendanceConfirmationMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;

class EventController extends Controller
{
    public function index()
    {
        $events = Event::with('user')
            ->where('is_active', true)
            ->where('end_date', '>', now())
            ->orderBy('start_date', 'asc')
            ->get();

        return view('events.index', compact('events'));
    }

    public function show(Event $event)
    {
        $event->load('user');

        return view('events.show', compact('event'));
    }

    public function register(Request $request, Event $event)
    {
        // Validate the request
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
        ], [
            'name.required' => 'الاسم مطلوب',
            'name.string' => 'يجب أن يكون الاسم نصاً',
            'name.max' => 'الاسم لا يجب أن يتجاوز 255 حرف',
            'email.required' => 'البريد الإلكتروني مطلوب',
            'email.email' => 'يجب أن يكون البريد الإلكتروني صحيحاً',
            'email.max' => 'البريد الإلكتروني لا يجب أن يتجاوز 255 حرف',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // Check if event is active and in the future
        if (!$event->is_active || $event->end_date < now()) {
            return back()->withErrors(['event' => 'هذا الحدث لم يعد متاحاً للتسجيل.'])->withInput();
        }

        // Check if user is already registered
        $existingRegistration = Attendance::where('event_id', $event->id)
            ->where('attendee_email', $request->email)
            ->first();

        if ($existingRegistration) {
            return back()->withErrors(['email' => 'أنت مسجل بالفعل في هذا الحدث.'])->withInput();
        }

        // Create attendance record
        $attendance = Attendance::create([
            'event_id' => $event->id,
            'attendee_name' => $request->name,
            'attendee_email' => $request->email,
            'qr_token' => 'attend_' . bin2hex(random_bytes(16)),
        ]);

        // Send confirmation email with QR code
        try {
            Mail::to($request->email)->send(new AttendanceConfirmationMail($attendance, $event));
        } catch (\Exception $e) {
            // Log the error but don't fail the registration
            \Log::error('Failed to send attendance confirmation email: ' . $e->getMessage());
        }

        return back()->with('success', 'تم التسجيل بنجاح! تم إرسال رمز QR لك عبر البريد الإلكتروني.');
    }

    /**
     * Validate QR code and check in attendee
     */
    public function validateQrCode(Request $request)
    {
        $request->validate([
            'qr_data' => 'required|string',
        ]);

        try {
            // Parse QR code data
            $qrData = json_decode($request->qr_data, true);

            if (!$qrData || !isset($qrData['type']) || $qrData['type'] !== 'attendance') {
                return response()->json([
                    'success' => false,
                    'message' => 'رمز QR غير صحيح'
                ], 400);
            }

            // Find attendance record
            $attendance = Attendance::where('event_id', $qrData['event_id'])
                ->where('qr_token', $qrData['token'])
                ->with('event')
                ->first();

            if (!$attendance) {
                return response()->json([
                    'success' => false,
                    'message' => 'لم يتم العثور على تسجيل صحيح'
                ], 404);
            }

            // Check if already checked in
            if ($attendance->isCheckedIn()) {
                return response()->json([
                    'success' => false,
                    'message' => 'تم تسجيل الحضور مسبقاً في ' . $attendance->used_at->format('Y-m-d H:i:s'),
                    'already_checked_in' => true,
                    'checked_in_at' => $attendance->used_at->format('Y-m-d H:i:s'),
                    'checked_in_by' => $attendance->checked_in_by
                ], 400);
            }

            // Check if event is still active
            if (!$attendance->event->is_active) {
                return response()->json([
                    'success' => false,
                    'message' => 'هذا الحدث لم يعد نشطاً'
                ], 400);
            }

            // Check if event date is valid
            if ($attendance->event->end_date < now()) {
                return response()->json([
                    'success' => false,
                    'message' => 'انتهت فترة هذا الحدث'
                ], 400);
            }

            // Check in the attendee
            $checkedInBy = $request->input('checked_in_by', auth()->user()->name ?? 'Scanner');
            $checkInResult = $attendance->checkIn($checkedInBy);

            if ($checkInResult) {
                return response()->json([
                    'success' => true,
                    'message' => 'تم تسجيل الحضور بنجاح',
                    'data' => [
                        'attendee_name' => $attendance->attendee_name,
                        'event_title' => $attendance->event->title,
                        'checked_in_at' => $attendance->used_at->format('Y-m-d H:i:s'),
                        'checked_in_by' => $attendance->checked_in_by
                    ]
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'فشل في تسجيل الحضور'
                ], 500);
            }

        } catch (\Exception $e) {
            \Log::error('QR Code validation error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ في معالجة رمز QR'
            ], 500);
        }
    }
}
