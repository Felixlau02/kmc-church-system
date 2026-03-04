<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\RoomBooking;
use Carbon\Carbon;

class RoomBookingController extends Controller
{
    public function index()
    {
        // Auto-archive past bookings
        $this->archivePastBookings();
        
        $bookings = RoomBooking::active()->latest()->paginate(10);
        return view('admin.roombooking.index', compact('bookings'));
    }

    public function history()
    {
        // Auto-archive first
        $this->archivePastBookings();
        
        $bookings = RoomBooking::archived()->latest()->paginate(10);
        return view('admin.roombooking.history', compact('bookings'));
    }

    public function createDirect()
    {
        $rooms = [
            'Room 1 (30 person)' => 'Room 1 (30 person)',
            'Room 2 (40 person)' => 'Room 2 (40 person)',
            'Room 3 (50 person)' => 'Room 3 (50 person)',
            'Main Hall (100 person)' => 'Main Hall (100 person)'
        ];
        return view('admin.roombooking.create-direct', compact('rooms'));
    }

    public function storeDirect(Request $request)
    {
        $validated = $request->validate([
            'room_id' => 'required|string|in:Room 1 (30 person),Room 2 (40 person),Room 3 (50 person),Main Hall (100 person)',
            'booking_date' => 'required|date|after_or_equal:today',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'member_name' => 'required|string',
            'boxing_description' => 'required|string'
        ]);

        // Check for conflicts
        $hasConflict = RoomBooking::hasConflict(
            $validated['room_id'],
            $validated['booking_date'],
            $validated['start_time'],
            $validated['end_time']
        );

        if ($hasConflict) {
            // Get conflicting bookings for detailed message
            $conflicts = RoomBooking::getConflictingBookings(
                $validated['room_id'],
                $validated['booking_date'],
                $validated['start_time'],
                $validated['end_time']
            );

            $conflictDetails = $conflicts->map(function ($booking) {
                return $booking->member_name . ' (' . 
                       Carbon::parse($booking->start_time)->format('g:i A') . ' - ' . 
                       Carbon::parse($booking->end_time)->format('g:i A') . ')';
            })->join(', ');

            return back()
                ->withInput()
                ->with('error', "Cannot create booking: The room is already booked during this time by {$conflictDetails}");
        }

        RoomBooking::create([
            'user_id' => auth()->id(),
            'room_id' => $validated['room_id'],
            'booking_date' => $validated['booking_date'],
            'start_time' => $validated['start_time'],
            'end_time' => $validated['end_time'],
            'booking_time' => $validated['start_time'],
            'member_name' => $validated['member_name'],
            'boxing_description' => $validated['boxing_description'],
            'status' => 'approved'
        ]);

        return redirect()->route('admin.roombooking.index')
            ->with('success', 'Direct booking created and approved successfully.');
    }

    public function edit(RoomBooking $roomBooking)
    {
        $rooms = [
            'Room 1 (30 person)' => 'Room 1 (30 person)',
            'Room 2 (40 person)' => 'Room 2 (40 person)',
            'Room 3 (50 person)' => 'Room 3 (50 person)',
            'Main Hall (100 person)' => 'Main Hall (100 person)'
        ];
        return view('admin.roombooking.edit', compact('roomBooking', 'rooms'));
    }

    public function update(Request $request, RoomBooking $roomBooking)
    {
        $validated = $request->validate([
            'room_id' => 'required|string|in:Room 1 (30 person),Room 2 (40 person),Room 3 (50 person),Main Hall (100 person)',
            'booking_date' => 'required|date',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'member_name' => 'required|string',
            'boxing_description' => 'required|string',
            'status' => 'required|in:pending,approved,rejected'
        ]);

        // If status is being changed to approved, check for conflicts
        if ($validated['status'] === 'approved') {
            $hasConflict = RoomBooking::hasConflict(
                $validated['room_id'],
                $validated['booking_date'],
                $validated['start_time'],
                $validated['end_time'],
                $roomBooking->id // Exclude current booking from conflict check
            );

            if ($hasConflict) {
                $conflicts = RoomBooking::getConflictingBookings(
                    $validated['room_id'],
                    $validated['booking_date'],
                    $validated['start_time'],
                    $validated['end_time'],
                    $roomBooking->id
                );

                $conflictDetails = $conflicts->map(function ($booking) {
                    return $booking->member_name . ' (' . 
                           Carbon::parse($booking->start_time)->format('g:i A') . ' - ' . 
                           Carbon::parse($booking->end_time)->format('g:i A') . ')';
                })->join(', ');

                return back()
                    ->withInput()
                    ->with('error', "Cannot approve booking: The room is already booked during this time by {$conflictDetails}");
            }
        }

        $validated['booking_time'] = $validated['start_time'];
        $roomBooking->update($validated);

        return redirect()->route('admin.roombooking.index')
            ->with('success', 'Booking updated successfully.');
    }

    public function destroy(RoomBooking $roomBooking)
    {
        $roomBooking->delete();
        return back()->with('success', 'Booking deleted successfully.');
    }

    public function approve(RoomBooking $roomBooking)
    {
        // Check for conflicts before approving
        $hasConflict = RoomBooking::hasConflict(
            $roomBooking->room_id,
            $roomBooking->booking_date,
            $roomBooking->start_time,
            $roomBooking->end_time,
            $roomBooking->id
        );

        if ($hasConflict) {
            $conflicts = RoomBooking::getConflictingBookings(
                $roomBooking->room_id,
                $roomBooking->booking_date,
                $roomBooking->start_time,
                $roomBooking->end_time,
                $roomBooking->id
            );

            $conflictDetails = $conflicts->map(function ($booking) {
                return $booking->member_name . ' (' . 
                       Carbon::parse($booking->start_time)->format('g:i A') . ' - ' . 
                       Carbon::parse($booking->end_time)->format('g:i A') . ')';
            })->join(', ');

            return back()->with('error', "Cannot approve booking: The room is already booked during this time by {$conflictDetails}");
        }

        $roomBooking->update(['status' => 'approved']);
        return back()->with('success', 'Booking approved successfully.');
    }

    public function reject(RoomBooking $roomBooking)
    {
        $roomBooking->update(['status' => 'rejected']);
        return back()->with('success', 'Booking rejected successfully.');
    }

    public function archive(RoomBooking $roomBooking)
    {
        $roomBooking->update(['is_archived' => true]);
        return back()->with('success', 'Booking archived successfully.');
    }

    private function archivePastBookings()
    {
        RoomBooking::where('is_archived', false)
            ->where('booking_date', '<', now()->toDateString())
            ->update(['is_archived' => true]);
    }
}