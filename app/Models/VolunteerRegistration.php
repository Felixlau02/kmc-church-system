<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VolunteerRegistration extends Model
{
    protected $fillable = [
        'activity_id',
        'team_id',
        'member_id',
        'role',
        'notes',
        'status'
    ];

    // Relationships
    public function activity(): BelongsTo
    {
        return $this->belongsTo(VolunteerActivity::class, 'activity_id');
    }

    public function team(): BelongsTo
    {
        return $this->belongsTo(VolunteerTeam::class, 'team_id');
    }

    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class, 'member_id');
    }

    // Scopes
    public function scopeForActivity($query, int $activityId)
    {
        return $query->where('activity_id', $activityId);
    }

    public function scopeForTeam($query, int $teamId)
    {
        return $query->where('team_id', $teamId);
    }

    public function scopeForMember($query, int $memberId)
    {
        return $query->where('member_id', $memberId);
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }

    // Helper methods
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isApproved(): bool
    {
        return $this->status === 'approved';
    }

    public function isRejected(): bool
    {
        return $this->status === 'rejected';
    }
}