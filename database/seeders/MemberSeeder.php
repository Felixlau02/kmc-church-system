<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Member;
use Illuminate\Support\Facades\DB;

class MemberSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear existing members (optional - remove if you want to keep existing data)
        // Member::truncate();

        // Array of realistic Chinese names
        $maleFirstNames = [
            'Wei Ming', 'Jun Hao', 'Kai Xin', 'Zhi Wei', 'Yi Chen',
            'Jun Wei', 'Wei Jie', 'Cheng Wei', 'Ming Hui', 'Zhi Hao',
            'Jun Jie', 'Wei Lun', 'Kai Yang', 'Yong Wei', 'Jia Wei',
            'Jun Ming', 'Xin Yi', 'Wei Han', 'Shi Wei', 'Cheng Jun'
        ];

        $femaleFirstNames = [
            'Mei Ling', 'Hui Min', 'Xiao Ling', 'Li Ying', 'Jia Yi',
            'Hui Ling', 'Yu Xin', 'Jia Ling', 'Shu Ting', 'Yi Ting',
            'Xin Yi', 'Mei Hui', 'Li Xin', 'Hui Xin', 'Jia Hui',
            'Shu Hui', 'Yu Ting', 'Xiao Hui', 'Mei Li', 'Li Hui'
        ];

        $lastNames = [
            'Tan', 'Lee', 'Wong', 'Lim', 'Chen', 'Ng', 'Ong', 'Teo',
            'Chang', 'Wang', 'Zhang', 'Liu', 'Yang', 'Huang',
            'Chong', 'Yap', 'Goh', 'Koh', 'Ho', 'Chew',
            'Low', 'Lau', 'Chin', 'Sim', 'Chia', 'Heng'
        ];

        // Array of email domains
        $domains = ['gmail.com', 'yahoo.com', 'hotmail.com', 'outlook.com', 'mail.com'];

        // Array of fellowship groups
        $fellowships = [
            'Junior Fellowship',
            'College Fellowship',
            'Youth Fellowship',
            'Adult Fellowship'
        ];

        // Array of baptism statuses
        $baptismStatuses = ['Yes', 'No'];

        // Create 50 random members
        for ($i = 1; $i <= 50; $i++) {
            // Randomly assign gender
            $gender = rand(0, 1) ? 'Male' : 'Female';
            
            // Select appropriate first name based on gender
            if ($gender == 'Male') {
                $firstName = $maleFirstNames[array_rand($maleFirstNames)];
            } else {
                $firstName = $femaleFirstNames[array_rand($femaleFirstNames)];
            }
            
            $lastName = $lastNames[array_rand($lastNames)];
            $fullName = $firstName . ' ' . $lastName;
            
            // Create email from name
            $emailName = strtolower(str_replace(' ', '.', $fullName));
            $domain = $domains[array_rand($domains)];
            $email = $emailName . rand(1, 999) . '@' . $domain;

            // Generate Malaysian phone number format
            $phonePrefix = ['012', '013', '014', '016', '017', '018', '019', '011', '010'];
            $prefix = $phonePrefix[array_rand($phonePrefix)];
            $phone = $prefix . '-' . rand(100, 999) . ' ' . rand(1000, 9999);

            // Randomly select fellowship (everyone must have one)
            $fellowship = $fellowships[array_rand($fellowships)];

            // Randomly select baptism status (70% chance of 'Yes', 30% 'No')
            $baptism = rand(1, 10) <= 7 ? 'Yes' : 'No';

            Member::create([
                'name' => $fullName,
                'email' => $email,
                'phone' => $phone,
                'gender' => $gender,
                'fellowship' => $fellowship,
                'baptism' => $baptism,
            ]);
        }
    }
}