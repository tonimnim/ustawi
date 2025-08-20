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
                'color' => '#10B981',
            ],
            [
                'name' => 'Youth Empowerment',
                'slug' => 'youth-empowerment',
                'description' => 'Stories and updates about youth programs and leadership',
                'color' => '#3B82F6',
            ],
            [
                'name' => 'Education',
                'slug' => 'education',
                'description' => 'Educational programs, scholarships, and learning opportunities',
                'color' => '#8B5CF6',
            ],
            [
                'name' => 'Health & Wellness',
                'slug' => 'health-wellness',
                'description' => 'Health initiatives, medical camps, and wellness programs',
                'color' => '#EF4444',
            ],
            [
                'name' => 'Environmental Conservation',
                'slug' => 'environmental-conservation',
                'description' => 'Environmental protection and sustainability initiatives',
                'color' => '#22C55E',
            ],
            [
                'name' => 'Success Stories',
                'slug' => 'success-stories',
                'description' => 'Inspiring stories from our beneficiaries and programs',
                'color' => '#F59E0B',
            ],
            [
                'name' => 'Events & Updates',
                'slug' => 'events-updates',
                'description' => 'Upcoming events, news, and organizational updates',
                'color' => '#06B6D4',
            ],
            [
                'name' => 'Partnerships',
                'slug' => 'partnerships',
                'description' => 'Collaborations and partnership announcements',
                'color' => '#EC4899',
            ],
        ];

        foreach ($categories as $index => $category) {
            DB::table('post_categories')->updateOrInsert(
                ['slug' => $category['slug']],
                array_merge($category, [
                    'sort_order' => $index + 1,
                    'is_active' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ])
            );
        }

        $this->command->info('Blog categories seeded successfully!');
    }
}