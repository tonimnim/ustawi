<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PostCategoriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'News & Updates',
                'slug' => 'news-updates',
                'description' => 'Latest news and updates from Ustawi Wa Jamii',
                'color' => '#3B82F6', // blue
                'sort_order' => 1,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Success Stories',
                'slug' => 'success-stories',
                'description' => 'Stories of impact and transformation',
                'color' => '#10B981', // green
                'sort_order' => 2,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Community Events',
                'slug' => 'community-events',
                'description' => 'Upcoming and past community events',
                'color' => '#F59E0B', // amber
                'sort_order' => 3,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Programs',
                'slug' => 'programs',
                'description' => 'Information about our programs and initiatives',
                'color' => '#8B5CF6', // purple
                'sort_order' => 4,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Resources',
                'slug' => 'resources',
                'description' => 'Educational resources and guides',
                'color' => '#EC4899', // pink
                'sort_order' => 5,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Announcements',
                'slug' => 'announcements',
                'description' => 'Important announcements and notices',
                'color' => '#EF4444', // red
                'sort_order' => 6,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        foreach ($categories as $category) {
            DB::table('post_categories')->updateOrInsert(
                ['slug' => $category['slug']],
                $category
            );
        }
    }
}