<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'type',
        'start_time',
        'end_time',
        'description',
        'location',
        'max_participants',
    ];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
    ];

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    public function members()
    {
        return $this->belongsToMany(Member::class, 'attendances')
            ->withPivot('status')
            ->withTimestamps();
    }

    public function registrations()
    {
        return $this->hasMany(EventRegistration::class);
    }

    public function registeredUsers()
    {
        return $this->belongsToMany(User::class, 'event_registrations')
            ->withPivot('status', 'notes')
            ->withTimestamps();
    }

    public function roomBookings()
    {
        return $this->hasMany(RoomBooking::class);
    }

    public function getStartAttribute()
    {
        return $this->start_time;
    }

    public function getEndAttribute()
    {
        return $this->end_time;
    }

    // Helper method to check if event is full
    public function isFull()
    {
        if (!$this->max_participants) {
            return false; // No limit set
        }
        
        $currentCount = $this->registrations()->where('status', '!=', 'cancelled')->count();
        return $currentCount >= $this->max_participants;
    }

    // Helper method to get available spots
    public function getAvailableSpots()
    {
        if (!$this->max_participants) {
            return null; // Unlimited
        }
        
        $currentCount = $this->registrations()->where('status', '!=', 'cancelled')->count();
        return max(0, $this->max_participants - $currentCount);
    }

    // Helper method to get current registration count
    public function getCurrentRegistrationCount()
    {
        return $this->registrations()->where('status', '!=', 'cancelled')->count();
    }
}