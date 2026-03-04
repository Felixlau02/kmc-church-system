<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use App\Models\Member;

class ProfileController extends Controller
{
    /**
     * Show the profile view page
     */
    public function show(Request $request)
    {
        // Get or create member profile
        $member = auth()->user()->member;
        
        if (!$member) {
            $member = Member::create([
                'user_id' => auth()->id(),
                'name' => auth()->user()->name,
                'email' => auth()->user()->email,
                'gender' => 'Male',
                'baptism' => 'No',
            ]);
        }
        
        return view('profile.show', compact('member'));
    }

    /**
     * Show the edit profile form
     */
    public function edit(Request $request)
    {
        // Get or create member profile
        $member = auth()->user()->member;
        
        if (!$member) {
            $member = Member::create([
                'user_id' => auth()->id(),
                'name' => auth()->user()->name,
                'email' => auth()->user()->email,
                'gender' => 'Male',
                'baptism' => 'No',
            ]);
        }
        
        return view('profile.edit', compact('member'));
    }

    /**
     * Update the profile
     */
    public function update(Request $request)
    {
        $user = auth()->user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'password' => ['nullable', 'confirmed', Password::defaults()],
            'phone' => ['nullable', 'regex:/^\+60 1\d{1,2} \d{3,4} \d{4}$/'],
            'gender' => ['required', 'in:Male,Female'],
            'fellowship' => ['nullable', 'string', 'max:255'],
            'baptism' => ['required', 'in:Yes,No'],
        ]);

        // Update user account
        $user->name = $validated['name'];
        $user->email = $validated['email'];

        // Update password if provided
        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        // Update or create member profile
        $member = $user->member;
        
        if (!$member) {
            $member = Member::create([
                'user_id' => $user->id,
                'name' => $validated['name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'] ?? null,
                'gender' => $validated['gender'],
                'fellowship' => $validated['fellowship'] ?? null,
                'baptism' => $validated['baptism'],
            ]);
        } else {
            $member->update([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'] ?? null,
                'gender' => $validated['gender'],
                'fellowship' => $validated['fellowship'] ?? null,
                'baptism' => $validated['baptism'],
            ]);
        }

        // Redirect back to profile page based on role
        if ($user->role === 'admin') {
            return redirect()->route('admin.profile.show')->with('success', 'Profile updated successfully!');
        }

        return redirect()->route('user.profile.show')->with('success', 'Profile updated successfully!');
    }
}