<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class SamplePostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get first category (or create one if none exists)
        $categoryId = DB::table('post_categories')->first()?->id;
        
        if (!$categoryId) {
            $categoryId = DB::table('post_categories')->insertGetId([
                'name' => 'Community Impact',
                'slug' => 'community-impact',
                'description' => 'Stories about our impact in communities',
                'color' => '#10B981',
                'sort_order' => 1,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
        
        // Get first user (admin)
        $userId = DB::table('users')->first()?->id ?? 1;
        
        // Sample posts
        $posts = [
            [
                'title' => 'Youth Empowerment Program Launches in Nairobi',
                'slug' => 'youth-empowerment-program-launches-nairobi',
                'excerpt' => 'We are excited to announce the launch of our new Youth Empowerment Program in Nairobi, designed to provide skills training and mentorship to young people aged 18-25.',
                'content' => '<p>We are thrilled to announce the launch of our comprehensive Youth Empowerment Program in Nairobi. This initiative represents a significant milestone in our mission to empower young people across Kenya.</p><p>The program, which will run for 12 months, aims to provide practical skills training, mentorship, and career guidance to 200 young people aged 18-25. Participants will receive training in:</p><ul><li>Digital literacy and basic computer skills</li><li>Entrepreneurship and business management</li><li>Financial literacy and money management</li><li>Leadership and communication skills</li><li>Job readiness and interview preparation</li></ul><p>Our team has partnered with local businesses and organizations to ensure that graduates of the program have access to internship and employment opportunities. This holistic approach ensures that young people not only gain skills but also have pathways to sustainable livelihoods.</p><p>The program launch event was attended by community leaders, partner organizations, and prospective participants. The enthusiasm and energy in the room were palpable as young people expressed their excitement about the opportunities ahead.</p><p>Applications for the program are now open and will close on March 31st, 2025. Interested young people can apply through our website or visit our offices in Nairobi for assistance with the application process.</p>',
                'featured_image' => 'https://images.unsplash.com/photo-1529390079861-591de354faf5?w=800&h=600&fit=crop',
                'status' => 'published',
                'published_at' => now()->subDays(2),
            ],
            [
                'title' => 'Clean Water Project Transforms Rural Community',
                'slug' => 'clean-water-project-transforms-rural-community',
                'excerpt' => 'Our clean water initiative in Kitui County has successfully provided access to clean, safe drinking water for over 5,000 community members.',
                'content' => '<p>Access to clean water is a fundamental human right, yet millions of people in rural Kenya still lack this basic necessity. Our latest clean water project in Kitui County is changing lives and transforming communities.</p><p>The project, completed last month, involved:</p><ul><li>Drilling of two deep boreholes</li><li>Installation of solar-powered water pumps</li><li>Construction of water storage tanks</li><li>Establishment of water distribution points</li><li>Training of community water management committees</li></ul><p>The impact has been immediate and profound. Women and children who previously walked for hours to fetch water can now access clean water within their communities. This has freed up time for education, economic activities, and family care.</p><p>Sarah Mwende, a mother of three, shared her experience: "Before this project, I would wake up at 4 AM to walk to the river. The water was often dirty, and my children frequently fell sick. Now, we have clean water just minutes from our home. My children are healthier, and I have time to tend to my farm and small business."</p><p>The project has also had unexpected benefits. School attendance has increased as children no longer miss classes to fetch water. Local health clinics report a significant decrease in waterborne diseases. Small businesses have emerged around the water points, creating economic opportunities.</p><p>This project was made possible through the generous support of our donors and partners. We are committed to replicating this model in other water-scarce communities across Kenya.</p>',
                'featured_image' => 'https://images.unsplash.com/photo-1541544537156-7627a7a4aa1c?w=800&h=600&fit=crop',
                'status' => 'published',
                'published_at' => now()->subDays(5),
            ],
            [
                'title' => 'Annual Fundraising Gala Raises Ksh 5 Million for Education',
                'slug' => 'annual-fundraising-gala-raises-5-million',
                'excerpt' => 'Our 2025 Annual Fundraising Gala was a tremendous success, raising over Ksh 5 million to support education programs for underprivileged children.',
                'content' => '<p>We are delighted to announce that our 2025 Annual Fundraising Gala exceeded all expectations, raising an incredible Ksh 5.2 million for our education programs. The event, held at the Sarova Stanley Hotel in Nairobi, brought together supporters, partners, and friends of Ustawi Wa Jamii.</p><p>The evening featured:</p><ul><li>Inspiring testimonials from program beneficiaries</li><li>A keynote address by renowned education advocate Dr. Mary Okello</li><li>Live entertainment by local artists</li><li>Silent and live auctions featuring artwork and experiences</li><li>Recognition of outstanding donors and volunteers</li></ul><p>The funds raised will directly support:</p><ul><li>School fees for 150 children from low-income families</li><li>Purchase of textbooks and learning materials</li><li>Teacher training programs in rural schools</li><li>Construction of two new classrooms in partner schools</li><li>Establishment of computer labs in three schools</li></ul><p>The highlight of the evening was when 10-year-old Faith Wanjiru, a beneficiary of our education program, took the stage to share her story. Her confidence and eloquence moved many to tears and inspired generous donations.</p><p>We extend our heartfelt gratitude to all who attended, donated, and supported this event. Your generosity will transform the lives of hundreds of children, giving them the opportunity to pursue their dreams through education.</p><p>Planning for next year\'s gala is already underway, and we look forward to another successful event that will enable us to expand our impact even further.</p>',
                'featured_image' => 'https://images.unsplash.com/photo-1511795409834-ef04bbd61622?w=800&h=600&fit=crop',
                'status' => 'published',
                'published_at' => now()->subDays(10),
            ],
            [
                'title' => 'Partnership with Tech Company Brings Digital Skills to Youth',
                'slug' => 'tech-partnership-digital-skills-youth',
                'excerpt' => 'A new partnership with SafariTech Solutions will provide free digital skills training to 500 young people across three counties.',
                'content' => '<p>We are excited to announce a groundbreaking partnership with SafariTech Solutions, one of Kenya\'s leading technology companies. This collaboration will bring much-needed digital skills training to young people in Nairobi, Kiambu, and Machakos counties.</p><p>The partnership includes:</p><ul><li>Free 6-month coding bootcamps for 500 youth</li><li>Provision of laptops and learning materials</li><li>Mentorship by SafariTech engineers and developers</li><li>Internship opportunities for top performers</li><li>Job placement assistance upon graduation</li></ul><p>In today\'s digital economy, technology skills are essential for employment and entrepreneurship. However, many young people lack access to quality training due to financial constraints. This partnership removes those barriers, providing world-class training at no cost to participants.</p><p>The curriculum will cover:</p><ul><li>Web development (HTML, CSS, JavaScript)</li><li>Mobile app development</li><li>Database management</li><li>Cloud computing basics</li><li>Cybersecurity fundamentals</li><li>Project management and soft skills</li></ul><p>James Mwangi, CEO of SafariTech Solutions, expressed his enthusiasm: "We believe in giving back to the community that has supported our growth. By investing in young people\'s digital skills, we are not only creating a pipeline of talent for the tech industry but also empowering youth to create their own opportunities."</p><p>Applications for the first cohort open next week. We encourage all interested young people to apply early as spaces are limited. This is a life-changing opportunity to gain skills that are in high demand globally.</p>',
                'featured_image' => 'https://images.unsplash.com/photo-1517245386807-bb43f82c33c4?w=800&h=600&fit=crop',
                'status' => 'published',
                'published_at' => now()->subDays(15),
            ],
        ];
        
        foreach ($posts as $post) {
            // Generate meta data
            $metaData = [
                'title' => Str::limit($post['title'], 60),
                'description' => Str::limit(strip_tags($post['excerpt']), 160),
                'keywords' => 'Ustawi Wa Jamii, community development, Kenya, ' . Str::slug($post['title'], ', '),
            ];
            
            DB::table('posts')->insert([
                'title' => $post['title'],
                'slug' => $post['slug'],
                'excerpt' => $post['excerpt'],
                'content' => $post['content'],
                'category_id' => $categoryId,
                'author_id' => $userId,
                'status' => $post['status'],
                'is_featured' => rand(0, 1) == 1,
                'allow_comments' => true,
                'published_at' => $post['published_at'],
                'featured_image' => $post['featured_image'],
                'meta_data' => json_encode($metaData),
                'views_count' => rand(50, 500),
                'created_at' => $post['published_at'],
                'updated_at' => $post['published_at'],
            ]);
        }
    }
}