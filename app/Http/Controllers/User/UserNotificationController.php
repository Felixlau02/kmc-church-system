<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Services\NotificationService;
use Illuminate\Support\Facades\Auth;

class UserNotificationController extends Controller
{
    protected $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    public function count()
    {
        $count = $this->notificationService->getUnreadCount(Auth::id());
        return response()->json(['count' => $count]);
    }

    public function markAsRead($id)
    {
        $notification = Notification::findOrFail($id);
        $this->notificationService->markAsRead($id, Auth::id());

        $redirectUrl = $this->notificationService->getRedirectUrl($notification, Auth::user()->role);

        if ($redirectUrl) {
            return redirect($redirectUrl);
        }

        return redirect()->back();
    }

    public function markAllAsRead()
    {
        $this->notificationService->markAllAsRead(Auth::id());
        return redirect()->back()->with('success', 'All notifications marked as read!');
    }
}