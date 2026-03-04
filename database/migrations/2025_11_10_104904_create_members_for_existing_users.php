<?php

use Illuminate\Database\Migrations\Migration;
use App\Models\User;
use App\Models\Member;

return new class extends Migration
{
    public function up(): void
    {
        // Create member profiles for existing users who don't have one
        $users = User::all();
        
        foreach ($users as $user) {
            // Check if user already has a member profile
            $existingMember = Member::where('email', $user->email)->first();
            
            if (!$existingMember) {
                Member::create([
                    'user_id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'phone' => null,
                    'gender' => 'Male', // Default
                    'fellowship' => null,
                    'baptism' => 'No', // Default
                ]);
            }
        }
    }

    public function down(): void
    {
        // Optional: Remove auto-created members
    }
};