<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\RoomBooking;
use App\Models\User;
use Carbon\Carbon;

class RoomBookingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get the admin user
        $admin = User::where('role', 'admin')->first();

        if (!$admin) {
            $this->command->warn('⚠️ No admin user found. Please run UserSeeder first.');
            return;
        }

        $memberNames = [
            'Tan Wei Ming',
            'Lee Hui Min',
            'Wong Jun Hao',
            'Lim Mei Ling',
            'Chen Kai Xin',
            'Ng Xiao Ling',
            'Ong Zhi Wei',
            'Teo Li Ying',
            'Chang Yi Chen',
            'Wang Jia Yi'
        ];

        $purposes = [
            'Sunday School teacher meeting',
            'Youth fellowship planning session',
            'Worship team practice',
            'Cell group leader training',
            'Prayer meeting preparation',
            'Church event planning discussion',
            'Bible study group session',
            'Ministry team coordination',
            'Outreach program planning',
            'Choir practice session'
        ];

        $rooms = ['Room 1', 'Room 2', 'Room 3'];
        $statuses = ['pending', 'approved', 'rejected'];

        // Create sample bookings for the admin user
        for ($i = 0; $i < 10; $i++) {
            $randomMemberName = $memberNames[array_rand($memberNames)];
            $randomRoom = $rooms[array_rand($rooms)];
            $randomStatus = $statuses[array_rand($statuses)];
            $randomPurpose = $purposes[array_rand($purposes)];

            RoomBooking::create([
                'user_id' => $admin->id,
                'room_id' => $randomRoom,
                'member_name' => $randomMemberName,
                'boxing_description' => $randomPurpose,
                'booking_date' => Carbon::now()->addDays(rand(1, 30))->format('Y-m-d'),
                'booking_time' => sprintf('%02d:%02d', rand(8, 17), rand(0, 1) * 30),
                'status' => $randomStatus
            ]);
        }

        $this->command->info('✅ Successfully created 10 sample room bookings for admin!');
    }
}