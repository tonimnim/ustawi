<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class BlogCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Community Development',
                'slug' => 'community-development',
                'description' => 'Articles about community empowerment and development initiatives',
                'color' => '#10B981', // green
            ],
            [
                'name' => 'Youth Empowerment',
                'slug' => 'youth-empowerment',
                'description' => 'Stories and updates about youth programs and leadership',
                'color' => '#3B82F6', // blue
            ],
            [
                'name' => 'Education',
                'slug' => 'education',
                'description' => 'Educational programs, scholarships, and learning opportunities',
                'color' => '#8B5CF6', // purple
            ],
            [
                'name' => 'Health & Wellness',
                'slug' => 'health-wellness',
                'description' => 'Health initiatives, medical camps, and wellness programs',
                'color' => '#EF4444', // red
            ],
            [
                'name' => 'Environmental Conservation',
                'slug' => 'environmental-conservation',
                'description' => 'Environmental protection and sustainability initiatives',
                'color' => '#22C55E', // green
            ],
            [
                'name' => 'Success Stories',
                'slug' => 'success-stories',
                'description' => 'Inspiring stories from our beneficiaries and programs',
                'color' => '#F59E0B', // amber
            ],
            [
                'name' => 'Events & Updates',
                'slug' => 'events-updates',
                'description' => 'Upcoming events, news, and organizational updates',
                'color' => '#06B6D4', // cyan
            ],
            [
                'name' => 'Partnerships',
                'slug' => 'partnerships',
                'description' => 'Collaborations and partnership announcements',
                'color' => '#EC4899', // pink
            ],
        ];

        foreach ($categories as $category) {
            DB::table('categories')->updateOrInsert(
                ['slug' => $category['slug']],
                array_merge($category, [
                    'created_at' => now(),
                    'updated_at' => now(),
                ])
            );
        }

        $this->command->info('Blog categories seeded successfully!');
    }
}