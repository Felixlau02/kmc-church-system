<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\RoomBooking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class UserRoomBookingController extends Controller
{
    public function index()
    {
        $bookings = RoomBooking::where('user_id', Auth::id())
            ->active()
            ->latest()
            ->get();
        
        return view('user.roombooking.index', compact('bookings'));
    }

    public function history()
    {
        $bookings = RoomBooking::where('user_id', Auth::id())
            ->archived()
            ->latest('updated_at')
            ->get();
        
        return view('user.roombooking.history', compact('bookings'));
    }

    public function create()
    {
        $rooms = [
            'Room 1 (30 person)' => 'Room 1 (30 person)',
            'Room 2 (40 person)' => 'Room 2 (40 person)',
            'Room 3 (50 person)' => 'Room 3 (50 person)',
            'Main Hall (100 person)' => 'Main Hall (100 person)'
        ];
        return view('user.roombooking.create', compact('rooms'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'room_id' => 'required|string|in:Room 1 (30 person),Room 2 (40 person),Room 3 (50 person),Main Hall (100 person)',
            'booking_date' => 'required|date|after_or_equal:today',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'boxing_description' => 'required|string'
        ]);

        // Check if user already has 2 or more approved bookings with future dates
        $activeApprovedBookings = RoomBooking::where('user_id', Auth::id())
            ->where('status', 'approved')
            ->where('is_archived', false)
            ->where('booking_date', '>=', now()->toDateString())
            ->count();

        if ($activeApprovedBookings >= 2) {
            return back()
                ->withInput()
                ->with('error', 'You have reached the maximum limit of 2 approved bookings. Please wait until one of your approved bookings has passed before making a new reservation.');
        }

        // Check for conflicts
        $hasConflict = RoomBooking::hasConflict(
            $validated['room_id'],
            $validated['booking_date'],
            $validated['start_time'],
            $validated['end_time']
        );

        // Automatically determine status based on conflict
        $status = $hasConflict ? 'rejected' : 'approved';

        $booking = RoomBooking::create([
            'user_id' => Auth::id(),
            'room_id' => $validated['room_id'],
            'member_name' => Auth::user()->name,
            'boxing_description' => $validated['boxing_description'],
            'booking_date' => $validated['booking_date'],
            'start_time' => $validated['start_time'],
            'end_time' => $validated['end_time'],
            'booking_time' => $validated['start_time'],
            'status' => $status,
            'is_archived' => false
        ]);

        // Prepare success message based on status
        if ($status === 'approved') {
            $message = 'Room booking approved successfully! The room is available for your requested time.';
        } else {
            // Get conflicting bookings to show in message
            $conflicts = RoomBooking::getConflictingBookings(
                $validated['room_id'],
                $validated['booking_date'],
                $validated['start_time'],
                $validated['end_time']
            );

            $conflictTimes = $conflicts->map(function ($booking) {
                return Carbon::parse($booking->start_time)->format('g:i A') . ' - ' . 
                       Carbon::parse($booking->end_time)->format('g:i A');
            })->join(', ');

            $message = "Booking rejected: The room is already booked during your requested time. Conflicting bookings: {$conflictTimes}";
        }

        return redirect()->route('user.roombooking.index')
            ->with('success', $message);
    }

    public function archive($id)
    {
        $booking = RoomBooking::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();
        
        $booking->update([
            'is_archived' => true
        ]);

        return redirect()->route('user.roombooking.index')
            ->with('success', 'Booking moved to history successfully!');
    }

    public function restore($id)
    {
        $booking = RoomBooking::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();
        
        $booking->update([
            'is_archived' => false
        ]);

        return redirect()->route('user.roombooking.history')
            ->with('success', 'Booking restored from history successfully!');
    }

    public function autoArchivePastBookings()
    {
        $yesterday = Carbon::yesterday()->endOfDay();
        
        $archivedCount = RoomBooking::where('user_id', Auth::id())
            ->where('is_archived', false)
            ->where('booking_date', '<', $yesterday)
            ->whereIn('status', ['approved', 'rejected'])
            ->update([
                'is_archived' => true
            ]);

        if ($archivedCount > 0) {
            return redirect()->route('user.roombooking.index')
                ->with('success', "$archivedCount past booking(s) moved to history automatically.");
        }

        return redirect()->route('user.roombooking.index');
    }
}