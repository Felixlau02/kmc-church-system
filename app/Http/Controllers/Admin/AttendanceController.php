<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\Member;
use App\Models\Attendance;

class AttendanceController extends Controller
{
    /**
     * Show general attendance page (list of events).
     */
    public function overview()
    {
        $events = Event::with('attendances')->get();
        return view('admin.attendance.index', compact('events'));
    }

    /**
     * Show attendance tracking page for a specific event.
     */
    public function track(Event $event)
    {
        // Check if the event date has arrived (event start date, not time)
        $eventDate = \Carbon\Carbon::parse($event->start_time)->startOfDay();
        $today = \Carbon\Carbon::today();
        
        // If event date hasn't arrived yet, redirect back with error
        if ($eventDate->isFuture()) {
            return redirect()->route('admin.attendance.index')
                ->with('error', 'You can only track attendance on or after the event date (' . $eventDate->format('M d, Y') . ')');
        }
        
        // Get the event date for consistent tracking
        $trackingDate = \Carbon\Carbon::parse($event->start_time)->toDateString();
        
        // Get only registered users for this event
        $registeredUserIds = \App\Models\EventRegistration::where('event_id', $event->id)
            ->where('status', '!=', 'cancelled')
            ->pluck('user_id');
        
        // If no users registered, show empty list with message
        if ($registeredUserIds->isEmpty()) {
            $members = collect();
            $attendances = collect();
            return view('admin.attendance.track', compact('event', 'members', 'attendances', 'trackingDate'))
                ->with('info', 'No members have registered for this event yet.');
        }
        
        // Get registered users
        $registeredUsers = \App\Models\User::whereIn('id', $registeredUserIds)->get();
        
        // Match users to members by email
        $registeredEmails = $registeredUsers->pluck('email')->filter();
        
        $members = Member::whereIn('email', $registeredEmails)
            ->orderBy('name')
            ->get();
        
        // If no members found by email matching, it means registered users don't have member profiles
        if ($members->isEmpty()) {
            return view('admin.attendance.track', compact('event', 'trackingDate'))
                ->with('members', collect())
                ->with('attendances', collect())
                ->with('warning', 'Registered users do not have member profiles. Please ensure users are added as members.');
        }
        
        // Get attendance records for the EVENT DATE
        $attendances = Attendance::where('event_id', $event->id)
            ->where('attendance_date', $trackingDate)
            ->get()
            ->keyBy('member_id');
        
        return view('admin.attendance.track', compact('event', 'members', 'attendances', 'trackingDate'));
    }

    /**
     * Store/update attendance for a specific event.
     */
    public function store(Request $request, $eventId)
    {
        $validated = $request->validate([
            'attendance' => 'required|array',
            'attendance.*' => 'required|in:present,absent,late',
        ]);

        $event = Event::findOrFail($eventId);
        // Use event date instead of current date
        $attendanceDate = \Carbon\Carbon::parse($event->start_time)->toDateString();

        // Loop through all attendance submissions
        foreach ($validated['attendance'] as $memberId => $status) {
            // Get existing attendance to check if it was self-marked
            $existingAttendance = Attendance::where('event_id', $eventId)
                ->where('member_id', $memberId)
                ->where('attendance_date', $attendanceDate)
                ->first();
            
            // Preserve self-marked note if it exists
            $notes = 'Recorded by admin at ' . now()->format('Y-m-d H:i:s');
            if ($existingAttendance && str_contains($existingAttendance->notes, 'Self-marked')) {
                $notes = $existingAttendance->notes . ' | Updated by admin at ' . now()->format('Y-m-d H:i:s');
            }
            
            Attendance::updateOrCreate(
                [
                    'event_id' => $eventId,
                    'member_id' => $memberId,
                    'attendance_date' => $attendanceDate,
                ],
                [
                    'status' => $status,
                    'notes' => $notes,
                ]
            );
        }

        return redirect()->back()->with('success', 'Attendance recorded successfully');
    }
}