<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\Notification;
use App\Models\RoomBooking;
use App\Services\NotificationService;
use Carbon\Carbon;

class EventController extends Controller
{
    protected $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    public function index(Request $request)
    {
        $filterType = $request->get('type');
        $query = Event::where('end_time', '>=', Carbon::now());
        
        if ($filterType && $filterType !== 'all') {
            $query->where('type', $filterType);
        }
        
        $events = $query->latest('start_time')->paginate(10);
        
        return view('admin.event.index', compact('events', 'filterType'));
    }

    public function history(Request $request)
    {
        $filterType = $request->get('type');
        $query = Event::where('end_time', '<', Carbon::now());
        
        if ($filterType && $filterType !== 'all') {
            $query->where('type', $filterType);
        }
        
        $events = $query->latest('start_time')->paginate(10);
        
        return view('admin.event.history', compact('events', 'filterType'));
    }

    public function create()
    {
        $rooms = ['Room 1', 'Room 2', 'Room 3', 'Main Hall', 'Other'];
        return view('admin.event.create', compact('rooms'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'type' => 'required',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after_or_equal:start_time',
            'location' => 'required|string',
            'location_other' => 'required_if:location,Other|nullable|string',
            'max_participants' => 'nullable|integer|min:1',
        ]);

        if ($request->location === 'Other' && $request->location_other) {
            $request->merge(['location' => $request->location_other]);
        }

        $event = Event::create($request->all());

        if ($request->location) {
            $this->createRoomBookingForEvent($event);
        }

        $this->notificationService->notifyNewEvent($event);

        return redirect()->route('admin.event.index')
            ->with('success', 'Event created successfully! ' . 
                   ($request->location ? 'Room booking has been automatically created and approved.' : 'Users have been notified.'));
    }

    public function edit(Event $event)
    {
        $rooms = ['Room 1', 'Room 2', 'Room 3', 'Main Hall', 'Other'];
        return view('admin.event.edit', compact('event', 'rooms'));
    }

    public function update(Request $request, Event $event)
    {
        $request->validate([
            'title' => 'required',
            'type' => 'required',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after_or_equal:start_time',
            'location' => 'required|string',
            'location_other' => 'required_if:location,Other|nullable|string',
            'max_participants' => 'nullable|integer|min:1',
        ]);

        // Check if reducing max_participants below current registration count
        $currentCount = $event->getCurrentRegistrationCount();
        if ($request->max_participants && $request->max_participants < $currentCount) {
            return redirect()->back()
                ->withInput()
                ->with('error', "Cannot set max participants to {$request->max_participants}. There are already {$currentCount} people registered.");
        }

        if ($request->location === 'Other' && $request->location_other) {
            $request->merge(['location' => $request->location_other]);
        }

        $oldLocation = $event->location;
        $event->update($request->all());

        if ($request->location && $request->location !== $oldLocation) {
            $event->roomBookings()->delete();
            $this->createRoomBookingForEvent($event);
        } elseif ($request->location) {
            $this->updateRoomBookingForEvent($event);
        } elseif (!$request->location && $oldLocation) {
            $event->roomBookings()->delete();
        }

        $this->notificationService->notifyEventUpdated($event);

        return redirect()->route('admin.event.index')
            ->with('success', 'Event updated successfully! Users have been notified.');
    }

    public function destroy(Event $event)
    {
        try {
            Notification::deleteRelatedNotifications(Event::class, $event->id);
            $event->roomBookings()->delete();
            $event->delete();

            session()->flash('notification_deleted', true);

            if (request()->has('from_history')) {
                return redirect()->route('admin.event.history')
                    ->with('success', 'Event, room bookings, and related notifications deleted successfully!');
            }

            return redirect()->route('admin.event.index')
                ->with('success', 'Event, room bookings, and related notifications deleted successfully!');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to delete event: ' . $e->getMessage());
        }
    }

    private function createRoomBookingForEvent(Event $event)
    {
        RoomBooking::create([
            'user_id' => auth()->id(),
            'event_id' => $event->id,
            'room_id' => $event->location,
            'member_name' => 'Event: ' . $event->title,
            'boxing_description' => $event->type . ' - ' . ($event->description ?? 'Church event'),
            'booking_date' => $event->start_time->toDateString(),
            'start_time' => $event->start_time->toTimeString(),
            'end_time' => $event->end_time->toTimeString(),
            'booking_time' => $event->start_time->toTimeString(),
            'status' => 'approved', 
            'is_archived' => false,
        ]);
    }

    private function updateRoomBookingForEvent(Event $event)
    {
        $booking = $event->roomBookings()->first();
        
        if ($booking) {
            $booking->update([
                'room_id' => $event->location,
                'member_name' => 'Event: ' . $event->title,
                'boxing_description' => $event->type . ' - ' . ($event->description ?? 'Church event'),
                'booking_date' => $event->start_time->toDateString(),
                'start_time' => $event->start_time->toTimeString(),  
                'end_time' => $event->end_time->toTimeString(),      
                'booking_time' => $event->start_time->toTimeString(),
            ]);
        } else {
            $this->createRoomBookingForEvent($event);
        }
    }
}