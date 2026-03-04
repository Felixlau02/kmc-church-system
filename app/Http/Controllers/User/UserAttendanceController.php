<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\EventRegistration;
use App\Models\Attendance;
use App\Models\Member;
use Carbon\Carbon;

class UserAttendanceController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        // Get all user's registrations with events
        $registrations = EventRegistration::with('event')
            ->where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();
        
        // Find member record for this user
        $member = Member::where('email', $user->email)->first();
        
        if (!$member && $user->phone) {
            $member = Member::where('phone', $user->phone)->first();
        }
        
        if (!$member && $user->name) {
            $member = Member::where('name', 'LIKE', '%' . $user->name . '%')->first();
        }
        
        // Get all attendance records marked by admin for this member
        $adminMarkedAttendances = [];
        if ($member) {
            $adminMarkedAttendances = Attendance::where('member_id', $member->id)
                ->pluck('event_id')
                ->toArray();
        }
        
        return view('user.attendance.index', compact('registrations', 'adminMarkedAttendances'));
    }
}