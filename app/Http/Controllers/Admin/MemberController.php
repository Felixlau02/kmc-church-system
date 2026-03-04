<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Member;

class MemberController extends Controller
{
    public function index(Request $request)
    {
        $query = Member::with('user');

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        // Filter by gender
        if ($request->filled('gender')) {
            $query->where('gender', $request->gender);
        }

        // Filter by fellowship
        if ($request->filled('fellowship')) {
            $query->where('fellowship', $request->fellowship);
        }

        // Filter by baptism status
        if ($request->filled('baptism')) {
            $query->where('baptism', $request->baptism);
        }

        // Sorting
        $sortField = $request->get('sort', 'created_at');
        $sortOrder = $request->get('order', 'desc');
        
        $allowedSorts = ['name', 'email', 'gender', 'fellowship', 'baptism', 'created_at'];
        if (in_array($sortField, $allowedSorts)) {
            $query->orderBy($sortField, $sortOrder);
        }

        $members = $query->paginate(10)->withQueryString();

        // Get filter options
        $fellowships = Member::distinct()->pluck('fellowship')->filter();
        
        return view('admin.member.index', compact('members', 'fellowships'));
    }

    public function create()
    {
        return view('admin.member.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:members,email',
            'phone' => 'nullable|string|max:20',
            'gender' => 'required|in:Male,Female',
            'fellowship' => 'nullable|string|max:255',
            'baptism' => 'required|in:Yes,No',
        ]);

        Member::create([
            'user_id' => auth()->id(),
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'gender' => $request->gender,
            'fellowship' => $request->fellowship,
            'baptism' => $request->baptism,
        ]);

        return redirect()->route('admin.member.index')->with('success', 'Member added successfully.');
    }

    public function show(Member $member)
    {
        $member->load(['user', 'attendances.event']);
        
        // Get attendance statistics
        $totalEvents = $member->events()->count();
        $attendedEvents = $member->attendances()->where('status', 'present')->count();
        $attendanceRate = $totalEvents > 0 ? round(($attendedEvents / $totalEvents) * 100, 1) : 0;
        
        // Get recent activities
        $recentAttendances = $member->attendances()
            ->with('event')
            ->latest()
            ->take(5)
            ->get();
        
        return view('admin.member.show', compact('member', 'attendanceRate', 'recentAttendances'));
    }

    public function edit(Member $member)
    {
        return view('admin.member.edit', compact('member'));
    }

    public function update(Request $request, Member $member)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:members,email,' . $member->id,
            'phone' => 'nullable|string|max:20',
            'gender' => 'required|in:Male,Female',
            'fellowship' => 'nullable|string|max:255',
            'baptism' => 'required|in:Yes,No',
        ]);

        $member->update($request->only([
            'name', 'email', 'phone', 'gender', 
            'fellowship', 'baptism'
        ]));
        
        return redirect()->route('admin.member.index')->with('success', 'Member updated successfully.');
    }

    public function destroy(Member $member)
    {
        $member->delete();
        return back()->with('success', 'Member deleted successfully.');
    }
}