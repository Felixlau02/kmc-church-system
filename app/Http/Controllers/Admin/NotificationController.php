<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    protected $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    public function index()
    {
        $notifications = Notification::with('creator')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin.notification.index', compact('notifications'));
    }

    public function create()
    {
        return view('admin.notification.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'message' => 'required|string',
            'type' => 'required|in:info,success,warning,error',
            'icon' => 'required|string|max:255',
            'action_url' => 'nullable|url|max:500',
            'target_audience' => 'required|in:all,members,admins',
        ]);

        $validated['is_active'] = true;
        $validated['created_by'] = Auth::id();

        Notification::create($validated);

        session()->flash('notification_created', true);

        return redirect()->route('admin.notification.index')
            ->with('success', 'Notification created and sent to users!');
    }

    public function edit(Notification $notification)
    {
        return view('admin.notification.edit', compact('notification'));
    }

    public function update(Request $request, Notification $notification)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'message' => 'required|string',
            'type' => 'required|in:info,success,warning,error',
            'icon' => 'required|string|max:255',
            'action_url' => 'nullable|url|max:500',
            'target_audience' => 'required|in:all,members,admins',
        ]);

        $notification->update($validated);

        return redirect()->route('admin.notification.index')
            ->with('success', 'Notification updated successfully!');
    }

    public function destroy(Notification $notification)
    {
        $notification->delete();

        return redirect()->route('admin.notification.index')
            ->with('success', 'Notification deleted successfully!');
    }

    public function toggle(Notification $notification)
    {
        $notification->update([
            'is_active' => !$notification->is_active
        ]);

        return redirect()->back()
            ->with('success', 'Notification status updated!');
    }

    /**
     * Get unread notification count
     */
    public function count()
    {
        $count = $this->notificationService->getUnreadCount(Auth::id());

        return response()->json(['count' => $count]);
    }

    /**
     * Mark notification as read and redirect to action URL
     * FIXED: Now uses NotificationService to generate proper URLs
     */
    public function markAsRead($id)
    {
        $notification = Notification::findOrFail($id);
        
        $this->notificationService->markAsRead($id, Auth::id());

        // Use the service to get the correct redirect URL based on user role
        $redirectUrl = $this->notificationService->getRedirectUrl($notification, Auth::user()->role);

        if ($redirectUrl) {
            return redirect($redirectUrl);
        }

        return redirect()->back();
    }

    /**
     * Mark all notifications as read
     */
    public function markAllAsRead()
    {
        $this->notificationService->markAllAsRead(Auth::id());

        return redirect()->back()
            ->with('success', 'All notifications marked as read!');
    }

    /**
     * Debug info for troubleshooting
     */
    public function debugInfo()
    {
        $userId = Auth::id();
        $user = Auth::user();
        
        $allNotifications = Notification::count();
        $activeNotifications = Notification::active()->count();
        $userNotifications = Notification::active()->forUser($userId)->count();
        $unreadNotifications = Notification::active()->forUser($userId)->unreadBy($userId)->count();

        return response()->json([
            'user_id' => $userId,
            'user_role' => $user->role ?? 'unknown',
            'all_notifications' => $allNotifications,
            'active_notifications' => $activeNotifications,
            'user_notifications' => $userNotifications,
            'unread_notifications' => $unreadNotifications,
        ]);
    }
}