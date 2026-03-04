<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Member;
use App\Models\RoomBooking;
use App\Models\Event;
use App\Models\VolunteerActivity;
use Carbon\Carbon;

class AdminController extends Controller
{
    public function home()
    {
        // Get statistics
        $totalMembers = Member::count();
        $totalBookings = RoomBooking::where('is_archived', false)->count();
        $pendingBookings = RoomBooking::where('status', 'pending')
            ->where('is_archived', false)
            ->count();
        
        // Events - using start_time (datetime column)
        $upcomingEvents = Event::where('start_time', '>=', now())->count();
        
        $thisWeekEvents = Event::whereBetween('start_time', [
            now()->startOfWeek(),
            now()->endOfWeek()
        ])->count();
        
        // Get active volunteers count
        try {
            $activeVolunteers = VolunteerActivity::count();
            $newVolunteers = VolunteerActivity::where('created_at', '>=', now()->subMonth())->count();
        } catch (\Exception $e) {
            $activeVolunteers = 0;
            $newVolunteers = 0;
        }
        
        // Get total sermons count
        try {
            $totalSermons = \App\Models\Sermon::count();
            $newSermons = \App\Models\Sermon::where('created_at', '>=', now()->subMonth())->count();
        } catch (\Exception $e) {
            $totalSermons = 0;
            $newSermons = 0;
        }
        
        // Get calendar data for events and approved bookings
        $calendarEvents = $this->getCalendarEvents();
        
        return view('admin.dashboard', compact(
            'totalMembers',
            'totalBookings',
            'pendingBookings',
            'upcomingEvents',
            'thisWeekEvents',
            'activeVolunteers',
            'newVolunteers',
            'totalSermons',
            'newSermons',
            'calendarEvents'
        ));
    }
    
    /**
     * Get all calendar events (Events and Approved Room Bookings)
     */
    private function getCalendarEvents()
{
    $events = [];
    
    // Get all events (upcoming and past for calendar view)
    $churchEvents = Event::all();
    foreach ($churchEvents as $event) {
        $events[] = [
            'id' => 'event-' . $event->id,
            'title' => $event->title,
            'start' => $event->start_time->toIso8601String(),
            'end' => $event->end_time->toIso8601String(),
            'backgroundColor' => '#10b981', // Green for events
            'borderColor' => '#059669',
            'type' => 'event',
            'description' => $event->description ?? '',
            'location' => $event->location ?? 'TBA',
            'eventType' => $event->type
        ];
    }
    
    // Get approved room bookings
    $roomBookings = RoomBooking::where('status', 'approved')
        ->where('is_archived', false)
        ->with('event') // Load the related event
        ->get();
        
    foreach ($roomBookings as $booking) {
        // If booking has an associated event, use the event's times
        if ($booking->event_id && $booking->event) {
            $startDateTime = $booking->event->start_time;
            $endDateTime = $booking->event->end_time;
        } else {
            // Otherwise, use booking date/time with 2-hour default duration
            $startDateTime = Carbon::parse($booking->booking_date . ' ' . $booking->booking_time);
            $endDateTime = $startDateTime->copy()->addHours(2);
        }
        
        $events[] = [
            'id' => 'booking-' . $booking->id,
            'title' => $booking->room_id . ' - ' . $booking->member_name,
            'start' => $startDateTime->toIso8601String(),
            'end' => $endDateTime->toIso8601String(),
            'backgroundColor' => '#3b82f6',
            'borderColor' => '#2563eb',
            'type' => 'booking',
            'room' => $booking->room_id,
            'memberName' => $booking->member_name,
            'description' => $booking->boxing_description
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