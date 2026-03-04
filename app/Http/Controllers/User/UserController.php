<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\EventRegistration;
use App\Models\RoomBooking;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function dashboard()
    {
        $user = Auth::user();
        
        // Get user's event registrations count (active only)
        $eventsRegisteredCount = EventRegistration::where('user_id', $user->id)
            ->where('status', '!=', 'cancelled')
            ->count();
        
        // Get user's room bookings count (active only)
        $roomBookingsCount = RoomBooking::where('user_id', $user->id)
            ->where('is_archived', false)
            ->count();
        
        // Get calendar data for events and room bookings
        $calendarEvents = $this->getCalendarEvents();
        
        return view('user.dashboard', compact(
            'eventsRegisteredCount',
            'roomBookingsCount',
            'calendarEvents'
        ));
    }
    
    /**
     * Get calendar events with registration status and room bookings
     */
    private function getCalendarEvents()
    {
        $events = [];
        $user = Auth::user();
        
        // Get only upcoming events (no past events)
        $churchEvents = Event::where('end_time', '>=', now())->get();
        
        // Get user's event registrations
        $userRegistrations = EventRegistration::where('user_id', $user->id)
            ->where('status', '!=', 'cancelled')
            ->pluck('event_id')
            ->toArray();
        
        // Add church events to calendar - ALL EVENTS ARE GREEN
        foreach ($churchEvents as $event) {
            $isRegistered = in_array($event->id, $userRegistrations);
            
            $events[] = [
                'id' => 'event-' . $event->id,
                'eventId' => $event->id,
                'title' => $event->title,
                'start' => $event->start_time->toIso8601String(),
                'end' => $event->end_time->toIso8601String(),
                'backgroundColor' => '#10b981',  // All events are green
                'borderColor' => '#059669',
                'description' => $event->description ?? '',
                'location' => $event->location ?? 'TBA',
                'type' => $event->type,
                'isRegistered' => $isRegistered,
                'registrationCount' => $event->registrations()->where('status', '!=', 'cancelled')->count(),
                'itemType' => 'event'
            ];
        }
        
        // Get user's room bookings (active only, future dates only)
        $roomBookings = RoomBooking::where('user_id', $user->id)
            ->where('is_archived', false)
            ->where('booking_date', '>=', now()->toDateString())
            ->get();
        
        // Add only APPROVED room bookings to calendar - CHANGED TO BLUE
        foreach ($roomBookings as $booking) {
            // Only show approved bookings
            if ($booking->status !== 'approved') {
                continue;
            }
            
            $startDateTime = Carbon::parse($booking->booking_date . ' ' . $booking->booking_time);
            
            // Calculate duration from booking_end_time if available, otherwise default to 3 hours
            if (isset($booking->booking_end_time) && $booking->booking_end_time) {
                $endDateTime = Carbon::parse($booking->booking_date . ' ' . $booking->booking_end_time);
            } else {
                $endDateTime = $startDateTime->copy()->addHours(3); // Default 3-hour duration
            }
            
            // Calculate duration in hours for display
            $durationHours = $startDateTime->diffInHours($endDateTime);
            
            $events[] = [
                'id' => 'booking-' . $booking->id,
                'bookingId' => $booking->id,
                'title' => $booking->room_id,  // REMOVED ROOM ICON
                'start' => $startDateTime->toIso8601String(),
                'end' => $endDateTime->toIso8601String(),
                'backgroundColor' => '#818cf8',  // Changed to blue
                'borderColor' => '#6366f1',
                'description' => $booking->boxing_description,
                'room' => $booking->room_id,
                'status' => $booking->status,
                'bookingTime' => $booking->booking_time,
                'bookingEndTime' => $booking->booking_end_time ?? null,
                'duration' => $durationHours,
                'itemType' => 'booking'
            ];
        }
        
        return json_encode($events);
    }
    
    /**
     * API endpoint for fetching calendar events (for AJAX refresh)
     */
    public function getCalendarData()
    {
        return response()->json(json_decode($this->getCalendarEvents()));
    }
}