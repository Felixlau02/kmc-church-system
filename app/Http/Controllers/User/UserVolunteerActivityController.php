<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\VolunteerActivity;
use App\Models\VolunteerRegistration;
use App\Models\VolunteerTeam;
use App\Models\Member;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class UserVolunteerActivityController extends Controller
{
    /**
     * Display all volunteer activities
     */
    public function index()
{
    // Show Sunday Service page directly instead of redirecting
    $activity = VolunteerActivity::where('activity_name', 'Sunday Service')
        ->with(['teams.members.member'])
        ->first();

    if (!$activity) {
        return redirect()->route('user.dashboard')
            ->with('error', 'Sunday Service activity not found.');
    }

    $memberId = $this->getUserMemberId();
    
    // Get user's member record
    $userMember = Member::where('user_id', Auth::id())->first();

    // Get teams with user's registration status
    $teams = $activity->teams()->with(['members' => function($query) use ($memberId) {
        $query->with('member');
    }])->get();

    // Check which teams user has requested/joined
    $userRegistrations = VolunteerRegistration::where('activity_id', $activity->id)
        ->where('member_id', $memberId)
        ->get()
        ->keyBy('team_id');

    // Get all taken roles per team (approved registrations only)
    $takenRoles = [];
    foreach ($teams as $team) {
        $takenRoles[$team->id] = VolunteerRegistration::where('team_id', $team->id)
            ->whereIn('status', ['pending', 'approved'])
            ->pluck('role')
            ->toArray();
    }

    return view('user.volunteer.sunday-service', compact(
        'activity',
        'teams',
        'userMember',
        'userRegistrations',
        'takenRoles'
    ));
}

    /**
     * Display Sunday Service teams and allow users to request to join
     */
    public function sundayService()
    {
        $activity = VolunteerActivity::where('activity_name', 'Sunday Service')
            ->with(['teams.members.member'])
            ->first();

        if (!$activity) {
            return redirect()->route('user.dashboard')
                ->with('error', 'Sunday Service activity not found.');
        }

        $memberId = $this->getUserMemberId();
        
        // Get user's member record
        $userMember = Member::where('user_id', Auth::id())->first();

        // Get teams with user's registration status
        $teams = $activity->teams()->with(['members' => function($query) use ($memberId) {
            $query->with('member');
        }])->get();

        // Check which teams user has requested/joined
        $userRegistrations = VolunteerRegistration::where('activity_id', $activity->id)
            ->where('member_id', $memberId)
            ->get()
            ->keyBy('team_id');

        // Get all taken roles per team (approved registrations only)
        $takenRoles = [];
        foreach ($teams as $team) {
            $takenRoles[$team->id] = VolunteerRegistration::where('team_id', $team->id)
                ->whereIn('status', ['pending', 'approved'])
                ->pluck('role')
                ->toArray();
        }

        return view('user.volunteer.sunday-service', compact(
            'activity',
            'teams',
            'userMember',
            'userRegistrations',
            'takenRoles'
        ));
    }

    /**
     * Request to join a team
     */
    public function requestJoinTeam(Request $request, int $teamId)
    {
        $validated = $request->validate([
            'role' => 'required|string|max:255',
            'notes' => 'nullable|string|max:500'
        ]);

        try {
            DB::beginTransaction();

            $team = VolunteerTeam::findOrFail($teamId);
            $memberId = $this->getUserMemberId();

            if (!$memberId) {
                return redirect()->back()
                    ->with('error', 'You need to create a member profile first.');
            }

            // Check if already requested/joined this team
            $exists = VolunteerRegistration::where('team_id', $teamId)
                ->where('member_id', $memberId)
                ->exists();

            if ($exists) {
                return redirect()->back()
                    ->with('info', 'You have already requested to join this team.');
            }

            // Check if role is already taken by approved registration
            $roleTaken = VolunteerRegistration::where('team_id', $teamId)
                ->where('role', $validated['role'])
                ->whereIn('status', ['pending', 'approved'])
                ->exists();

            if ($roleTaken) {
                return redirect()->back()
                    ->with('error', 'This role has already been taken by another member.');
            }

            // Create registration request with pending status
            VolunteerRegistration::create([
                'activity_id' => $team->activity_id,
                'team_id' => $teamId,
                'member_id' => $memberId,
                'role' => $validated['role'],
                'notes' => $validated['notes'] ?? null,
                'status' => 'pending'
            ]);

            DB::commit();
            return redirect()->back()
                ->with('success', 'Your request to join the team has been submitted! Please wait for admin approval.');
            
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Failed to submit request: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Cancel team join request
     */
    public function cancelTeamRequest(int $teamId)
    {
        try {
            $memberId = $this->getUserMemberId();

            $registration = VolunteerRegistration::where('team_id', $teamId)
                ->where('member_id', $memberId)
                ->firstOrFail();

            $teamName = $registration->team->team_name;
            $registration->delete();

            return redirect()->back()
                ->with('success', "You have left {$teamName}.");
            
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to cancel request: ' . $e->getMessage());
        }
    }

    /**
     * Register for an activity (legacy method for backward compatibility)
     */
    public function register($id)
    {
        $activity = VolunteerActivity::findOrFail($id);
        
        // Check if activity date has passed
        if ($activity->activity_date < now()->toDateString()) {
            return back()->with('error', 'Cannot register for past activities.');
        }

        $memberId = $this->getUserMemberId();

        if (!$memberId) {
            return back()->with('error', 'You need to create a member profile first.');
        }

        // Check if already registered
        $exists = VolunteerRegistration::where('activity_id', $id)
            ->where('member_id', $memberId)
            ->exists();

        if ($exists) {
            return back()->with('info', 'You are already registered for this activity.');
        }

        try {
            VolunteerRegistration::create([
                'activity_id' => $id,
                'member_id' => $memberId,
                'status' => 'pending'
            ]);
            
            return back()->with('success', 'Successfully registered! We look forward to seeing you.');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to register. Please try again.');
        }
    }

    /**
     * Unregister from an activity (legacy method for backward compatibility)
     */
    public function unregister($id)
    {
        $activity = VolunteerActivity::findOrFail($id);
        
        // Check if activity date has passed
        if ($activity->activity_date < now()->toDateString()) {
            return back()->with('error', 'Cannot unregister from past activities.');
        }

        try {
            $memberId = $this->getUserMemberId();

            VolunteerRegistration::where('activity_id', $id)
                ->where('member_id', $memberId)
                ->delete();
                
            return back()->with('success', 'Successfully unregistered from this activity.');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to unregister. Please try again.');
        }
    }

    /**
     * Helper method to get user's member ID
     */
    private function getUserMemberId()
    {
        $member = Member::where('user_id', Auth::id())->first();
        return $member ? $member->id : null;
    }
}