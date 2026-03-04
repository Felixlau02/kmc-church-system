<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Member;

class SyncUsersToMembers extends Command
{
    protected $signature = 'sync:users-to-members';
    protected $description = 'Sync all users to members table';

    public function handle()
    {
        $users = User::all();
        $synced = 0;
        
        foreach ($users as $user) {
            $member = Member::firstOrCreate(
                ['email' => $user->email],
                [
                    'name' => $user->name,
                    'phone' => null,
                    'gender' => null,
                    'fellowship' => null,
                    'baptism' => null,
                ]
            );
            
            if ($member->wasRecentlyCreated) {
                $this->info("✓ Synced: {$user->name} ({$user->email})");
                $synced++;
            } else {
                $this->line("- Already exists: {$user->name}");
            }
        }
        
        $this->info("\n✅ Sync complete! {$synced} new member(s) created.");
        return 0;
    }
}