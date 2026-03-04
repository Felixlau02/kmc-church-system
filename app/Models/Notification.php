<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Carbon\Carbon;

class Notification extends Model
{
    protected $fillable = [
        'title',
        'message',
        'type',
        'icon',
        'action_url',
        'target_audience',
        'is_active',
        'created_by',
        'related_type', // NEW: Store the model type (Event, Sermon, etc.)
        'related_id',   // NEW: Store the related model ID
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // Relationships
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function reads(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'notification_reads')
            ->withPivot('read_at')
            ->withTimestamps();
    }

    // Polymorphic relationship for the related model
    public function related()
    {
        return $this->morphTo();
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeForUser($query, $userId)
    {
        $user = User::find($userId);
        
        return $query->where(function ($q) use ($user) {
            $q->where('target_audience', 'all')
              ->orWhere(function ($subQ) use ($user) {
                  if ($user && $user->role === 'admin') {
                      $subQ->where('target_audience', 'admins');
                  } else {
                      $subQ->where('target_audience', 'members');
                  }
              });
        });
    }

    public function scopeUnreadBy($query, $userId)
    {
        return $query->whereDoesntHave('reads', function ($q) use ($userId) {
            $q->where('user_id', $userId);
        });
    }

    // NEW: Scope to filter by related model
    public function scopeForRelated($query, $modelType, $modelId)
    {
        return $query->where('related_type', $modelType)
                     ->where('related_id', $modelId);
    }

    // Helper Methods
    public function isReadBy($userId): bool
    {
        return $this->reads()->where('user_id', $userId)->exists();
    }

    public function markAsReadBy($userId): void
    {
        if (!$this->isReadBy($userId)) {
            $this->reads()->attach($userId, [
                'read_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    public function getTimeAgoAttribute(): string
    {
        return $this->created_at->diffForHumans();
    }

    // Static helper to create notifications
    public static function createNotification(array $data): self
    {
        return self::create([
            'title' => $data['title'],
            'message' => $data['message'],
            'type' => $data['type'] ?? 'info',
            'icon' => $data['icon'] ?? 'fa-bell',
            'action_url' => $data['action_url'] ?? null,
            'target_audience' => $data['target_audience'] ?? 'all',
            'is_active' => true,
            'created_by' => auth()->id(),
            'related_type' => $data['related_type'] ?? null,  // NEW
            'related_id' => $data['related_id'] ?? null,      // NEW
        ]);
    }

    // NEW: Static method to delete related notifications
    public static function deleteRelatedNotifications($modelType, $modelId): void
    {
        self::where('related_type', $modelType)
            ->where('related_id', $modelId)
            ->delete();
    }
}