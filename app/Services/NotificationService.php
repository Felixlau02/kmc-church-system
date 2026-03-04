<?php

namespace App\Services;

use App\Models\Notification;
use App\Models\Event;
use App\Models\Sermon;
use App\Models\VolunteerActivity;
use App\Models\VolunteerTeam;

class NotificationService
{
    /**
     * Send notification when a new event is created
     */
    public function notifyNewEvent(Event $event): void
    {
        $notification = Notification::createNotification([
            'title' => 'New Event: ' . $event->title,
            'message' => 'A new event "' . $event->title . '" has been scheduled for ' . $event->start_time->format('M d, Y'),
            'type' => 'info',
            'icon' => 'fa-calendar-alt',
            'action_url' => $event->id, 
            'target_audience' => 'all',
            'related_type' => Event::class,  
            'related_id' => $event->id,      
        ]);

        session()->flash('notification_created', true);
    }

    /**
     * Send notification when a new sermon is uploaded
     */
    public function notifyNewSermon(Sermon $sermon): void
    {
        $notification = Notification::createNotification([
            'title' => 'New Sermon Available',
            'message' => 'A new sermon "' . $sermon->sermon_theme . '" by ' . $sermon->reverend_name . ' is now available',
            'type' => 'success',
            'icon' => 'fa-microphone',
            'action_url' => $sermon->id, 
            'target_audience' => 'all',
            'related_type' => Sermon::class,  
            'related_id' => $sermon->id,      
        ]);

        session()->flash('notification_created', true);
    }

    /**
     * Send notification when a volunteer activity is created
     */
    public function notifyNewVolunteerActivity(VolunteerActivity $activity): void
    {
        $notification = Notification::createNotification([
            'title' => 'New Volunteer Opportunity',
            'message' => 'Join us for "' . $activity->activity_name . '" on ' . $activity->activity_date->format('M d, Y'),
            'type' => 'info',
            'icon' => 'fa-hands-helping',
            'action_url' => 'volunteer', 
            'target_audience' => 'all',
            'related_type' => VolunteerActivity::class,  
            'related_id' => $activity->id,              
        ]);

        session()->flash('notification_created', true);
    }

    /**
     * Send notification when a volunteer activity is published
     */
    public function notifyVolunteerActivityPublished(VolunteerActivity $activity): void
    {
        if ($activity->isPublished()) {
            $notification = Notification::createNotification([
                'title' => 'Volunteer Activity Published',
                'message' => '"' . $activity->activity_name . '" is now open for registration!',
                'type' => 'success',
                'icon' => 'fa-hands-helping',
                'action_url' => 'volunteer',
                'target_audience' => 'all',
                'related_type' => VolunteerActivity::class,  
                'related_id' => $activity->id,               
            ]);

            session()->flash('notification_created', true);
        }
    }

    /**
     * Send notification when a new volunteer team is created
     */
    public function notifyNewVolunteerTeam(VolunteerTeam $team): void
    {
        $notification = Notification::createNotification([
            'title' => 'New Volunteer Team: ' . $team->team_name,
            'message' => 'A new volunteer team "' . $team->team_name . '" has been created. Join now to serve together!',
            'type' => 'info',
            'icon' => 'fa-users',
            'action_url' => 'volunteer',
            'target_audience' => 'all',
            'related_type' => VolunteerTeam::class,
            'related_id' => $team->id,
        ]);

        session()->flash('notification_created', true);
    }

    /**
     * Send notification when an event is updated
     */
    public function notifyEventUpdated(Event $event): void
    {
        $notification = Notification::createNotification([
            'title' => 'Event Updated: ' . $event->title,
            'message' => 'The event "' . $event->title . '" has been updated. Please check the details.',
            'type' => 'warning',
            'icon' => 'fa-calendar-alt',
            'action_url' => $event->id,
            'target_audience' => 'all',
            'related_type' => Event::class, 
            'related_id' => $event->id,      
        ]);

        session()->flash('notification_created', true);
    }

    /**
     * Send notification when sermon processing is complete
     */
    public function notifySermonProcessed(Sermon $sermon): void
    {
        if ($sermon->isProcessed()) {
            $notification = Notification::createNotification([
                'title' => 'Sermon Ready: ' . $sermon->sermon_theme,
                'message' => 'The sermon "' . $sermon->sermon_theme . '" has been processed and is ready to view with transcript and translations.',
                'type' => 'success',
                'icon' => 'fa-microphone',
                'action_url' => $sermon->id,
                'target_audience' => 'all',
                'related_type' => Sermon::class,  
                'related_id' => $sermon->id,      
            ]);

            session()->flash('notification_created', true);
        }
    }

    /**
     * Get unread notification count for a user
     */
    public function getUnreadCount($userId): int
    {
        return Notification::active()
            ->forUser($userId)
            ->unreadBy($userId)
            ->count();
    }

    /**
     * Mark notification as read
     */
    public function markAsRead($notificationId, $userId): void
    {
        $notification = Notification::find($notificationId);
        if ($notification) {
            $notification->markAsReadBy($userId);
        }
    }

    /**
     * Mark all notifications as read for a user
     */
    public function markAllAsRead($userId): void
    {
        $notifications = Notification::active()
            ->forUser($userId)
            ->unreadBy($userId)
            ->get();

        foreach ($notifications as $notification) {
            $notification->markAsReadBy($userId);
        }
    }

    /**
     * Build the correct redirect URL based on notification data and user role
     */
    public function getRedirectUrl(Notification $notification, $userRole): ?string
    {
        if (!$notification->action_url) {
            return null;
        }

        $prefix = $userRole === 'admin' ? 'admin' : 'user';
        
        // Map notification types to routes
        if ($notification->related_type === Event::class) {
            return route("{$prefix}.event.index");
        } elseif ($notification->related_type === Sermon::class) {
            return route("{$prefix}.sermon.index");
        } elseif ($notification->related_type === VolunteerActivity::class) {
            return route("{$prefix}.volunteer.index");
        } elseif ($notification->related_type === VolunteerTeam::class) {
            return route("{$prefix}.volunteer.index");
        }
        
        // Fallback: if action_url is just a string identifier
        if ($notification->action_url === 'volunteer') {
            return route("{$prefix}.volunteer.index");
        }
        
        // If action_url is a number (ID), try to determine the type from icon
        if (is_numeric($notification->action_url)) {
            if (str_contains($notification->icon, 'calendar')) {
                return route("{$prefix}.event.index");
            } elseif (str_contains($notification->icon, 'microphone')) {
                return route("{$prefix}.sermon.index");
            } elseif (str_contains($notification->icon, 'users') || str_contains($notification->icon, 'hands')) {
                return route("{$prefix}.volunteer.index");
            }
        }
        
        return null;
    }
}