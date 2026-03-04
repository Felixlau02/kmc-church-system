<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class VolunteerActivity extends Model
{
    protected $fillable = [
        'activity_name',
        'description',
        'activity_date',
        'activity_time',
        'status'
    ];

    protected $casts = [
        'activity_date' => 'date',
        'activity_time' => 'datetime:H:i',
    ];

    // Relationships
    public function teams(): HasMany
    {
        return $this->hasMany(VolunteerTeam::class, 'activity_id')
                    ->orderBy('sort_order');
    }

    public function registrations(): HasMany
    {
        return $this->hasMany(VolunteerRegistration::class, 'activity_id');
    }

    // Scopes
    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    public function scopeUpcoming($query)
    {
        return $query->where('activity_date', '>=', now()->toDateString())
                    ->orderBy('activity_date');
    }

    // Helper methods
    public function getTotalVolunteersAttribute(): int
    {
        return $this->registrations()->count();
    }

    public function getTotalTeamsAttribute(): int
    {
        return $this->teams()->count();
    }

    public function isPublished(): bool
    {
        return $this->status === 'published';
    }

    public function isDraft(): bool
    {
        return $this->status === 'draft';
    }
}