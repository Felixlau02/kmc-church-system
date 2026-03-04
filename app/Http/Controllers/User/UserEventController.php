<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\EventRegistration;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class UserEventController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $query = Event::where('end_time', '>', Carbon::now());
        
        if ($request->has('type') && $request->type) {
            $type = str_replace('-', ' ', $request->type);
            $type = ucwords($type);
            $query->where('type', $type);
        }
        
        $events = $query->latest()->paginate(10);
            
        $userRegistrations = EventRegistration::where('user_id', Auth::id())
            ->where('status', '!=', 'cancelled')
            ->pluck('event_id')
            ->toArray();

        return view('user.event.index', compact('events', 'userRegistrations'));
    }

    public function show(Event $event)
    {
        if (Carbon::parse($event->end_time)->isPast()) {
            return redirect()->route('user.event.index')
                ->with('error', 'This event has already ended.');
        }

        $userRegistration = EventRegistration::where('event_id', $event->id)
            ->where('user_id', Auth::id())
            ->where('status', '!=', 'cancelled')
            ->first();

        $isRegistered = $userRegistration !== null;
        $registrationCount = $event->getCurrentRegistrationCount();
        $isFull = $event->isFull();
        $availableSpots = $event->getAvailableSpots();

        return view('user.event.show', compact('event', 'isRegistered', 'userRegistration', 'registrationCount', 'isFull', 'availableSpots'));
    }

    public function register(Event $event)
    {
        if (Carbon::parse($event->end_time)->isPast()) {
            return redirect()->back()->with('error', 'Cannot register for an event that has already ended.');
        }

        // Check if event is full
        if ($event->isFull()) {
            return redirect()->back()->with('error', 'This event is already full. Registration is closed.');
        }

        $existingRegistration = EventRegistration::where('event_id', $event->id)
            ->where('user_id', Auth::id())
            ->first();

        if ($existingRegistration) {
            if ($existingRegistration->status !== 'cancelled') {
                return redirect()->back()->with('error', 'You are already registered for this event.');
            }
            
            // Check again if event is full before reactivating
            if ($event->isFull()) {
                return redirect()->back()->with('error', 'This event is already full. Registration is closed.');
            }
            
            $existingRegistration->update(['status' => 'registered']);
            
            return redirect()->back()->with('success', 'Successfully registered for the event!');
        }

        EventRegistration::create([
            'event_id' => $event->id,
            'user_id' => Auth::id(),
            'status' => 'registered',
        ]);

        return redirect()->back()->with('success', 'Successfully registered for the event!');
    }

    public function cancel(Event $event)
    {
        $registration = EventRegistration::where('event_id', $event->id)
            ->where('user_id', Auth::id())
            ->where('status', '!=', 'cancelled')
            ->first();

        if (!$registration) {
            return redirect()->back()->with('error', 'Registration not found.');
        }

        $registration->update(['status' => 'cancelled']);

        return redirect()->back()->with('success', 'Registration cancelled successfully.');
    }
}