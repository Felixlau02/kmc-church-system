<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Event;
use Carbon\Carbon;

class EventSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Define event data
        $events = [
            [
                'title' => 'Sunday Morning Worship Service',
                'type' => 'Sunday Service',
                'description' => 'Join us for our weekly Sunday worship service featuring inspiring messages, worship music, and fellowship with the community.',
                'location' => 'Main Church Hall',
                'start_time' => Carbon::now()->addDays(1)->setHour(9)->setMinute(0)->setSecond(0),
                'end_time' => Carbon::now()->addDays(1)->setHour(11)->setMinute(0)->setSecond(0),
            ],
            [
                'title' => 'Monday Prayer Meeting',
                'type' => 'Prayer Meeting',
                'description' => 'Weekly prayer gathering where members come together to intercede for the church, community, and the world.',
                'location' => 'Prayer Room',
                'start_time' => Carbon::now()->addDays(2)->setHour(19)->setMinute(0)->setSecond(0),
                'end_time' => Carbon::now()->addDays(2)->setHour(20)->setMinute(30)->setSecond(0),
            ],
            [
                'title' => 'Cell Group Meeting - North Zone',
                'type' => 'Cell Meeting',
                'description' => 'Small group gathering for deeper discussion, Bible study, and community building. New members welcome!',
                'location' => 'Community Center, Building A',
                'start_time' => Carbon::now()->addDays(3)->setHour(19)->setMinute(30)->setSecond(0),
                'end_time' => Carbon::now()->addDays(3)->setHour(21)->setMinute(0)->setSecond(0),
            ],
            [
                'title' => 'Youth Outreach Event',
                'type' => 'Sunday Service',
                'description' => 'Special event targeting young adults with contemporary worship, interactive sessions, and social activities.',
                'location' => 'Youth Center',
                'start_time' => Carbon::now()->addDays(5)->setHour(18)->setMinute(0)->setSecond(0),
                'end_time' => Carbon::now()->addDays(5)->setHour(20)->setMinute(30)->setSecond(0),
            ],
            [
                'title' => 'Wednesday Evening Bible Study',
                'type' => 'Prayer Meeting',
                'description' => 'In-depth study of Scripture with discussion and Q&A. Suitable for all levels of faith maturity.',
                'location' => 'Main Church Hall',
                'start_time' => Carbon::now()->addDays(4)->setHour(19)->setMinute(0)->setSecond(0),
                'end_time' => Carbon::now()->addDays(4)->setHour(20)->setMinute(45)->setSecond(0),
            ],
            [
                'title' => 'Praise and Worship Night',
                'type' => 'Sunday Service',
                'description' => 'Evening of passionate worship and adoration. Featuring live band and extended prayer time.',
                'location' => 'Main Church Hall',
                'start_time' => Carbon::now()->addDays(8)->setHour(19)->setMinute(0)->setSecond(0),
                'end_time' => Carbon::now()->addDays(8)->setHour(21)->setMinute(0)->setSecond(0),
            ],
            [
                'title' => 'Cell Group Meeting - South Zone',
                'type' => 'Cell Meeting',
                'description' => 'Small group gathering for deeper discussion, Bible study, and community building. New members welcome!',
                'location' => 'Community Center, Building B',
                'start_time' => Carbon::now()->addDays(6)->setHour(19)->setMinute(30)->setSecond(0),
                'end_time' => Carbon::now()->addDays(6)->setHour(21)->setMinute(0)->setSecond(0),
            ],
            [
                'title' => 'Monthly Prayer Vigil',
                'type' => 'Prayer Meeting',
                'description' => '24-hour prayer marathon. Join for one hour or more. All prayer styles welcome.',
                'location' => 'Prayer Room',
                'start_time' => Carbon::now()->addDays(15)->setHour(6)->setMinute(0)->setSecond(0),
                'end_time' => Carbon::now()->addDays(16)->setHour(6)->setMinute(0)->setSecond(0),
            ],
        ];

        // Create events
        foreach ($events as $eventData) {
            Event::create($eventData);
        }

        $this->command->info('✅ Successfully created ' . count($events) . ' events!');
    }
}