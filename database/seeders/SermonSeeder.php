<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Sermon;
use Carbon\Carbon;

class SermonSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $sermons = [
            [
                'reverend_name' => 'Rev. John Smith',
                'sermon_theme' => 'Walking in Faith: Trusting God\'s Plan',
                'sermon_description' => 'Discover how faith shapes our journey and teaches us to trust in God\'s perfect plan for our lives. In this powerful message, we explore what it truly means to walk by faith and not by sight. Learn how to overcome doubt and fear by anchoring your trust in God\'s unchanging character and promises.',
                'sermon_date' => Carbon::now()->subDays(10)->format('Y-m-d'),
                'scripture_references' => 'John 3:16, Romans 8:28, Hebrews 11:1',
                'category' => 'Faith',
            ],
            [
                'reverend_name' => 'Rev. Sarah Johnson',
                'sermon_theme' => 'Grace: The Ultimate Gift',
                'sermon_description' => 'Understanding the transformative power of grace and how it changes everything about our relationship with God. Grace is not just about forgiveness; it\'s about being empowered to live a new life. Join us as we explore the depths of God\'s grace and how we can extend it to others.',
                'sermon_date' => Carbon::now()->subDays(8)->format('Y-m-d'),
                'scripture_references' => 'Ephesians 2:8-9, 2 Corinthians 12:9',
                'category' => 'Grace',
            ],
            [
                'reverend_name' => 'Rev. Michael Chen',
                'sermon_theme' => 'Building Strong Foundations',
                'sermon_description' => 'Learn how to build a solid spiritual foundation that will support you through life\'s challenges. A strong foundation requires commitment, consistency, and biblical truth. Discover the practical steps to developing a faith that stands firm when storms come.',
                'sermon_date' => Carbon::now()->subDays(15)->format('Y-m-d'),
                'scripture_references' => 'Matthew 7:24-27, Proverbs 10:25',
                'category' => 'Life',
            ],
            [
                'reverend_name' => 'Rev. Emily Rodriguez',
                'sermon_theme' => 'Love in Action',
                'sermon_description' => 'Explore practical ways to express and live out the love that Jesus taught us to share with others. True love isn\'t just a feeling—it\'s a choice and an action. Learn how to demonstrate Christ\'s love through service, compassion, and sacrifice in your daily life.',
                'sermon_date' => Carbon::now()->subDays(5)->format('Y-m-d'),
                'scripture_references' => '1 John 4:7-12, 1 Corinthians 13:4-7, John 13:34-35',
                'category' => 'Love',
            ],
            [
                'reverend_name' => 'Rev. David Williams',
                'sermon_theme' => 'Hope: Anchor for the Soul',
                'sermon_description' => 'In times of uncertainty and despair, hope is our anchor. This message explores the biblical foundation of hope and how it differs from mere wishful thinking. Discover how a genuine hope in God\'s promises can sustain you through any circumstance.',
                'sermon_date' => Carbon::now()->subDays(3)->format('Y-m-d'),
                'scripture_references' => 'Hebrews 6:19, Romans 15:13, 1 Peter 1:3',
                'category' => 'Hope',
            ],
            [
                'reverend_name' => 'Rev. Lisa Anderson',
                'sermon_theme' => 'Finding Peace in Chaos',
                'sermon_description' => 'The peace of God transcends all understanding and guards our hearts and minds. In this world of constant turmoil and anxiety, learn how to access the supernatural peace that only God can provide. Transform your mind and find tranquility in Christ.',
                'sermon_date' => Carbon::now()->subDays(1)->format('Y-m-d'),
                'scripture_references' => 'Philippians 4:6-7, John 14:27, Psalm 29:11',
                'category' => 'Peace',
            ],
            [
                'reverend_name' => 'Rev. James Thompson',
                'sermon_theme' => 'Forgiveness: Breaking Free',
                'sermon_description' => 'Holding grudges and unforgiveness is like drinking poison and expecting someone else to die. In this liberating message, learn the transformative power of forgiveness—both receiving it from God and extending it to others. Forgiveness is not weakness; it\'s freedom.',
                'sermon_date' => Carbon::now()->subDays(7)->format('Y-m-d'),
                'scripture_references' => 'Matthew 18:21-35, Colossians 3:13, Ephesians 4:32',
                'category' => 'Grace',
            ],
            [
                'reverend_name' => 'Rev. Patricia Green',
                'sermon_theme' => 'Purpose: Living with Direction',
                'sermon_description' => 'Discover God\'s purpose for your life and learn how to align your goals with His kingdom plans. Many people drift through life without direction. This sermon will challenge you to define your purpose and take intentional steps toward your God-given calling.',
                'sermon_date' => Carbon::now()->subDays(12)->format('Y-m-d'),
                'scripture_references' => 'Proverbs 19:21, Jeremiah 29:11, Ephesians 2:10',
                'category' => 'Life',
            ],
            [
                'reverend_name' => 'Rev. Robert Martinez',
                'sermon_theme' => 'Strength Through Surrender',
                'sermon_description' => 'Paradoxically, true strength comes not through control, but through surrender to God\'s will. Learn how letting go of your plans and trusting God\'s direction can lead to a more abundant and peaceful life. Discover the power found in weakness.',
                'sermon_date' => Carbon::now()->subDays(20)->format('Y-m-d'),
                'scripture_references' => '2 Corinthians 12:9-10, Philippians 4:13, Proverbs 3:5-6',
                'category' => 'Faith',
            ],
            [
                'reverend_name' => 'Rev. Margaret White',
                'sermon_theme' => 'Community: The Body of Christ',
                'sermon_description' => 'We are not meant to walk this journey alone. Explore the biblical foundation of Christian community and how the church functions as the body of Christ. Learn practical ways to build deeper connections and support one another in faith.',
                'sermon_date' => Carbon::now()->subDays(2)->format('Y-m-d'),
                'scripture_references' => '1 Corinthians 12:12-27, Hebrews 10:24-25, 1 Thessalonians 5:11',
                'category' => 'Life',
            ],
            [
                'reverend_name' => 'Rev. John Smith',
                'sermon_theme' => 'Overcoming Fear with Faith',
                'sermon_description' => 'Fear is a tool the enemy uses to paralyze believers. This powerful message explores biblical courage and how to overcome fear through faith in God. Discover the promises God has made to those who trust in Him despite their circumstances.',
                'sermon_date' => Carbon::now()->subDays(6)->format('Y-m-d'),
                'scripture_references' => '2 Timothy 1:7, Psalm 27:1, Joshua 1:8-9',
                'category' => 'Faith',
            ],
            [
                'reverend_name' => 'Rev. Sarah Johnson',
                'sermon_theme' => 'Serving Others: Jesus\' Example',
                'sermon_description' => 'Jesus taught us that greatness in the kingdom comes through service. In this inspiring message, explore the servant heart of Christ and how we are called to follow His example. Learn why serving others is not a burden but a privilege.',
                'sermon_date' => Carbon::now()->subDays(9)->format('Y-m-d'),
                'scripture_references' => 'Matthew 23:11, Mark 10:45, John 13:12-17',
                'category' => 'Love',
            ],
            [
                'reverend_name' => 'Rev. Michael Chen',
                'sermon_theme' => 'Prayer: Conversing with God',
                'sermon_description' => 'Prayer is not a religious ritual—it\'s a personal conversation with your Creator. Learn the transformative power of prayer and how to develop a consistent prayer life. Discover different prayer models and how to intercede effectively for others.',
                'sermon_date' => Carbon::now()->subDays(14)->format('Y-m-d'),
                'scripture_references' => '1 Thessalonians 5:17, Matthew 6:6, Philippians 4:6',
                'category' => 'Faith',
            ],
            [
                'reverend_name' => 'Rev. Emily Rodriguez',
                'sermon_theme' => 'Loving Your Enemies',
                'sermon_description' => 'One of Jesus\' most challenging commands is to love our enemies. In this revolutionary message, explore what this means in practical terms and how it can transform conflict into compassion. Learn to love as Christ loved—unconditionally and sacrificially.',
                'sermon_date' => Carbon::now()->subDays(11)->format('Y-m-d'),
                'scripture_references' => 'Matthew 5:43-48, Luke 6:27-36, Romans 12:20-21',
                'category' => 'Love',
            ],
            [
                'reverend_name' => 'Rev. David Williams',
                'sermon_theme' => 'Eternal Life: The Promise of Heaven',
                'sermon_description' => 'Death need not be feared by believers who understand God\'s promise of eternal life. This message brings comfort and clarity about what heaven is and what awaits those who believe in Christ. Discover how the hope of heaven can change the way you live today.',
                'sermon_date' => Carbon::now()->subDays(18)->format('Y-m-d'),
                'scripture_references' => 'John 3:16, 1 John 5:11-13, Revelation 21:4',
                'category' => 'Hope',
            ],
        ];

        foreach ($sermons as $sermon) {
            Sermon::create($sermon);
        }

        $this->command->info('Successfully seeded ' . count($sermons) . ' sermons!');
    }
}