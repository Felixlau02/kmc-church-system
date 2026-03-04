<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
    use HasFactory;
    
    protected $table = 'members';
    protected $fillable = ['name', 'email', 'phone', 'user_id', 'gender', 'fellowship', 'baptism'];

    /**
     * Get the user that owns this member profile (One-to-One)
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get all attendance records for this member
     */
    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    /**
     * Get all events this member attended
     */
    public function events()
    {
        return $this->belongsToMany(Event::class, 'attendances')
            ->withPivot('status')
            ->withTimestamps();
    }
}