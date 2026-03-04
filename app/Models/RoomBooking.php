<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class RoomBooking extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'event_id',
        'room_id',
        'member_name',
        'boxing_description',
        'booking_date',
        'booking_time',
        'start_time',   
        'end_time', 
        'status',
        'is_archived',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_archived', false);
    }

    public function scopeArchived($query)
    {
        return $query->where('is_archived', true);
    }

    /**
     * Check if there's a conflicting booking for the given room, date, and time range
     * 
     * @param string $roomId
     * @param string $bookingDate
     * @param string $startTime
     * @param string $endTime
     * @param int|null $excludeBookingId - ID to exclude from check (for updates)
     * @return bool
     */
    public static function hasConflict($roomId, $bookingDate, $startTime, $endTime, $excludeBookingId = null)
    {
        $query = self::where('room_id', $roomId)
            ->where('booking_date', $bookingDate)
            ->where('status', 'approved') // Only check against approved bookings
            ->where('is_archived', false);

        if ($excludeBookingId) {
            $query->where('id', '!=', $excludeBookingId);
        }

        // Check for time overlap
        $conflicts = $query->where(function ($q) use ($startTime, $endTime) {
            // Case 1: New booking starts during an existing booking
            $q->where(function ($subQ) use ($startTime) {
                $subQ->where('start_time', '<=', $startTime)
                     ->where('end_time', '>', $startTime);
            })
            // Case 2: New booking ends during an existing booking
            ->orWhere(function ($subQ) use ($endTime) {
                $subQ->where('start_time', '<', $endTime)
                     ->where('end_time', '>=', $endTime);
            })
            // Case 3: New booking completely encompasses an existing booking
            ->orWhere(function ($subQ) use ($startTime, $endTime) {
                $subQ->where('start_time', '>=', $startTime)
                     ->where('end_time', '<=', $endTime);
            });
        })->exists();

        return $conflicts;
    }

    /**
     * Get conflicting bookings for display
     * 
     * @param string $roomId
     * @param string $bookingDate
     * @param string $startTime
     * @param string $endTime
     * @param int|null $excludeBookingId
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function getConflictingBookings($roomId, $bookingDate, $startTime, $endTime, $excludeBookingId = null)
    {
        $query = self::where('room_id', $roomId)
            ->where('booking_date', $bookingDate)
            ->where('status', 'approved')
            ->where('is_archived', false);

        if ($excludeBookingId) {
            $query->where('id', '!=', $excludeBookingId);
        }

        return $query->where(function ($q) use ($startTime, $endTime) {
            $q->where(function ($subQ) use ($startTime) {
                $subQ->where('start_time', '<=', $startTime)
                     ->where('end_time', '>', $startTime);
            })
            ->orWhere(function ($subQ) use ($endTime) {
                $subQ->where('start_time', '<', $endTime)
                     ->where('end_time', '>=', $endTime);
            })
            ->orWhere(function ($subQ) use ($startTime, $endTime) {
                $subQ->where('start_time', '>=', $startTime)
                     ->where('end_time', '<=', $endTime);
            });
        })->get();
    }
}