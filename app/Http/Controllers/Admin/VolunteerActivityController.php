<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Member;
use App\Models\Notification;
use App\Models\VolunteerActivity;
use App\Models\VolunteerRegistration;
use App\Models\VolunteerTeam;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VolunteerActivityController extends Controller
{
    protected $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    /**
     * Display the Sunday Service management page
     */
    public function index()
    {
        $activity = VolunteerActivity::with(['teams.members.member'])
            ->firstOrCreate(
                ['activity_name' => 'Sunday Service'],
                [
                    'description' => 'Weekly Sunday Service Schedule',
                    'activity_date' => now(),
                    'status' => 'published'
                ]
            );

        $members = Member::orderBy('name')->get();
        $teamTypes = VolunteerTeam::getTeamTypes();
        
        return view('admin.volunteer.index', compact('activity', 'members', 'teamTypes'));
    }

    /**
     * Add a new team to the activity
     */
    public function addTeam(Request $request)
    {
        $validated = $request->validate([
            'team_name' => 'required|string|max:255',
            'team_type' => 'required|string|in:worship,usher,technical,other'
        ]);

        try {
            DB::beginTransaction();

            $activity = VolunteerActivity::where('activity_name', 'Sunday Service')->firstOrFail();
            $maxOrder = $activity->teams()->max('sort_order') ?? 0;
            
            $team = VolunteerTeam::create([
                'activity_id' => $activity->id,
                'team_name' => $validated['team_name'],
                'team_type' => $validated['team_type'],
                'sort_order' => $maxOrder + 1
            ]);

            // Send notification about new team
            $this->notificationService->notifyNewVolunteerTeam($team);

            DB::commit();
            return redirect()->back()->with('success', 'Team added successfully and notification sent to all users!');
            
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Failed to add team: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display team management page
     */
    public function manageTeam(int $teamId)
    {
        $team = VolunteerTeam::with(['members.member', 'activity'])
            ->findOrFail($teamId);
        
        $members = Member::orderBy('name')->get();
        $teamTypes = VolunteerTeam::getTeamTypes();
        $availableRoles = VolunteerTeam::getRolesByType($team->team_type);
        
        return view('admin.volunteer.manage-team', compact(
            'team',
            'members',
            'teamTypes',
            'availableRoles'
        ));
    }

    /**
     * Update team details
     */
    public function updateTeam(Request $request, int $teamId)
    {
        $validated = $request->validate([
            'team_name' => 'required|string|max:255',
            'team_type' => 'required|string|in:worship,usher,technical,other'
        ]);

        try {
            $team = VolunteerTeam::findOrFail($teamId);
            $team->update($validated);
            
            return redirect()->back()->with('success', 'Team updated successfully!');
            
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to update team: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Delete a team
     */
    public function deleteTeam(int $teamId)
    {
        try {
            DB::beginTransaction();

            $team = VolunteerTeam::findOrFail($teamId);
            $activityId = $team->activity_id;
            
            // Delete related notifications
            Notification::where('related_type', VolunteerTeam::class)
                ->where('related_id', $teamId)
                ->delete();
            
            // Delete team (cascade will handle registrations)
            $team->delete();
            
            // Reorder remaining teams
            $this->reorderTeams($activityId);

            DB::commit();
            
            session()->flash('notification_deleted', true);
            
            return redirect()->back()->with('success', 'Team and related notifications deleted successfully!');
            
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Failed to delete team: ' . $e->getMessage());
        }
    }

    /**
     * Delete a volunteer activity (if you add this feature in the future)
     */
    public function destroy($id)
    {
        try {
            DB::beginTransaction();

            $activity = VolunteerActivity::findOrFail($id);

            // Delete related notifications before deleting the activity
            Notification::where('related_type', VolunteerActivity::class)
                ->where('related_id', $id)
                ->delete();

            // Also delete notifications by action_url pattern (for older notifications)
            Notification::where('action_url', 'like', '%/volunteer%')
                ->where(function ($query) use ($activity) {
                    $query->where('message', 'like', '%' . $activity->activity_name . '%')
                          ->orWhere('title', 'like', '%' . $activity->activity_name . '%');
                })
                ->delete();

            // Delete the activity
            $activity->delete();

            DB::commit();

            session()->flash('notification_deleted', true);

            return redirect()->route('admin.volunteer.index')
                ->with('success', 'Activity and related notifications deleted successfully!');

        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()
                ->with('error', 'Failed to delete activity: ' . $e->getMessage());
        }
    }

    /**
     * Add a member to a team (admin adds are auto-approved)
     */
    public function addMember(Request $request, int $teamId)
    {
        $validated = $request->validate([
            'member_id' => 'required|exists:members,id',
            'role' => 'required|string|max:255',
            'notes' => 'nullable|string|max:500'
        ]);

        try {
            DB::beginTransaction();

            $team = VolunteerTeam::findOrFail($teamId);
            
            // Check if member already exists in this team
            $exists = VolunteerRegistration::where('team_id', $teamId)
                ->where('member_id', $validated['member_id'])
                ->exists();

            if ($exists) {
                return redirect()->back()->with('error', 'This member is already in the team.');
            }

            // Check if role is already taken by approved member
            $roleTaken = VolunteerRegistration::where('team_id', $teamId)
                ->where('role', $validated['role'])
                ->where('status', 'approved')
                ->exists();

            if ($roleTaken) {
                return redirect()->back()
                    ->with('error', 'This role has already been taken by another member.');
            }

            VolunteerRegistration::create([
                'activity_id' => $team->activity_id,
                'team_id' => $teamId,
                'member_id' => $validated['member_id'],
                'role' => $validated['role'],
                'notes' => $validated['notes'] ?? null,
                'status' => 'approved' // Admin adds are automatically approved
            ]);

            DB::commit();
            return redirect()->back()->with('success', 'Member added to team successfully!');
            
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Failed to add member: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Update member registration details
     */
    public function updateMember(Request $request, int $registrationId)
    {
        $validated = $request->validate([
            'role' => 'required|string|max:255',
            'notes' => 'nullable|string|max:500'
        ]);

        try {
            $registration = VolunteerRegistration::findOrFail($registrationId);
            
            $registration->update([
                'role' => $validated['role'],
                'notes' => $validated['notes'] ?? null
            ]);
            
            return redirect()->back()->with('success', 'Member details updated successfully!');
            
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to update member: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove a member from a team
     */
    public function removeMember(int $registrationId)
    {
        try {
            $registration = VolunteerRegistration::findOrFail($registrationId);
            $registration->delete();
            
            return redirect()->back()->with('success', 'Member removed from team successfully!');
            
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to remove member: ' . $e->getMessage());
        }
    }

    /**
     * Approve a pending registration request
     */
    public function approveRequest(int $registrationId)
    {
        try {
            $registration = VolunteerRegistration::findOrFail($registrationId);
            
            // Check if role is already taken by another approved member
            $roleTaken = VolunteerRegistration::where('team_id', $registration->team_id)
                ->where('role', $registration->role)
                ->where('status', 'approved')
                ->where('id', '!=', $registrationId)
                ->exists();

            if ($roleTaken) {
                return redirect()->back()
                    ->with('error', 'This role has already been taken by another member.');
            }
            
            $registration->update(['status' => 'approved']);
            
            return redirect()->back()
                ->with('success', $registration->member->name . ' has been approved for ' . $registration->role . '!');
            
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to approve request: ' . $e->getMessage());
        }
    }

    /**
     * Reject a pending registration request
     */
    public function rejectRequest(int $registrationId)
    {
        try {
            $registration = VolunteerRegistration::findOrFail($registrationId);
            $memberName = $registration->member->name;
            $role = $registration->role;
            
            $registration->delete();
            
            return redirect()->back()
                ->with('success', $memberName . '\'s request for ' . $role . ' has been rejected. The role is now available for others.');
            
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to reject request: ' . $e->getMessage());
        }
    }

    /**
     * Reorder teams after deletion
     */
    private function reorderTeams(int $activityId): void
    {
        $teams = VolunteerTeam::where('activity_id', $activityId)
            ->orderBy('sort_order')
            ->get();

        foreach ($teams as $index => $team) {
            $team->update(['sort_order' => $index + 1]);
        }
    }

    /**
     * Update team sort order (for drag & drop)
     */
    public function updateTeamOrder(Request $request)
    {
        $validated = $request->validate([
            'teams' => 'required|array',
            'teams.*.id' => 'required|exists:volunteer_teams,id',
            'teams.*.sort_order' => 'required|integer|min:0'
        ]);

        try {
            DB::beginTransaction();

            foreach ($validated['teams'] as $teamData) {
                VolunteerTeam::where('id', $teamData['id'])
                    ->update(['sort_order' => $teamData['sort_order']]);
            }

            DB::commit();
            return response()->json(['success' => true, 'message' => 'Team order updated!']);
            
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
}