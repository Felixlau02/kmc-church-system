<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class VolunteerTeam extends Model
{
    protected $fillable = [
        'activity_id',
        'team_name',
        'team_type',
        'sort_order'
    ];

    // Relationships
    public function activity(): BelongsTo
    {
        return $this->belongsTo(VolunteerActivity::class, 'activity_id');
    }

    public function members(): HasMany
    {
        return $this->hasMany(VolunteerRegistration::class, 'team_id');
    }

    // Static helper methods
    public static function getTeamTypes(): array
    {
        return [
            'worship' => 'Worship Team',
            'usher' => 'Usher Team',
            'technical' => 'Technical Team',
            'other' => 'Other'
        ];
    }

    public static function getRolesByType(string $teamType): array
    {
        $roles = [
            'worship' => [
                'Worship Leader',
                'Guitarist',
                'Pianist',
                'Bassist',
                'Drummer',
                'Vocalist',
                'Backing Vocal'
            ],
            'usher' => [
                'Head Usher',
                'Usher',
                'Greeter',
                'Door Keeper'
            ],
            'technical' => [
                'Sound Engineer',
                'Lighting Technician',
                'Video Operator',
                'Live Stream Operator',
                'Presentation Operator'
            ],
            'other' => [
                'Volunteer',
                'Helper',
                'Assistant'
            ]
        ];

        return $roles[$teamType] ?? ['Member'];
    }

    // Helper methods
    public function getMembersCountAttribute(): int
    {
        return $this->members()->count();
    }

    public function getTeamTypeNameAttribute(): string
    {
        return self::getTeamTypes()[$this->team_type] ?? 'Unknown';
    }
}