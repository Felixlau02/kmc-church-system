<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Member;

class UserMemberController extends Controller
{
    /**
     * Show the member directory (all members - read only)
     */
    public function index(Request $request)
    {
        $query = Member::query();

        // Search functionality
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('fellowship', 'like', "%{$search}%");
            });
        }

        // Filter by gender
        if ($request->has('gender') && $request->gender != '') {
            $query->where('gender', $request->gender);
        }

        // Filter by fellowship
        if ($request->has('fellowship') && $request->fellowship != '') {
            $query->where('fellowship', $request->fellowship);
        }

        // Filter by baptism
        if ($request->has('baptism') && $request->baptism != '') {
            $query->where('baptism', $request->baptism);
        }

        $members = $query->orderBy('name', 'asc')->paginate(12);

        // Get unique fellowships for filter dropdown
        $fellowships = Member::select('fellowship')
            ->distinct()
            ->whereNotNull('fellowship')
            ->where('fellowship', '!=', '')
            ->pluck('fellowship');

        return view('user.member.index', compact('members', 'fellowships'));
    }

    /**
     * Show a specific member's details (read only)
     */
    public function show($id)
    {
        $member = Member::findOrFail($id);
        return view('user.member.show', compact('member'));
    }
}