<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class HomeController extends Controller
{
    /**
     * Display the homepage.
     */
    public function index(): Response
    {
        // Get homepage settings including images
        $settings = $this->getHomeSettings();
        
        return Inertia::render('Homepage/Index', [
            'settings' => $settings,
        ]);
    }

    /**
     * Display the programs page.
     */
    public function programs(): Response
    {
        $settings = $this->getHomeSettings();
        
        return Inertia::render('Programs/Index', [
            'settings' => $settings,
        ]);
    }

    /**
     * Get homepage settings from database.
     */
    private function getHomeSettings(): array
    {
        try {
            $dbSettings = \DB::table('site_settings')->pluck('value', 'key')->toArray();
        } catch (\Exception $e) {
            $dbSettings = [];
        }
        
        // Default homepage settings
        $defaults = [
            'organization_name' => 'Ustawi Wa Jamii',
            'organization_description' => 'Empowering communities through sustainable development and youth leadership across Kenya.',
            'mission_statement' => 'To empower youth and communities through sustainable development initiatives, capacity building, and innovative solutions that create lasting positive impact across Kenya.',
            'contact_email' => 'info@ustawiwajamii.org',
            'contact_phone' => '+254 700 000 000',
            'physical_address' => 'Nairobi, Kenya',
            'homepage_images' => [],
            'facebook_url' => '',
            'twitter_url' => '',
            'instagram_url' => '',
            'linkedin_url' => '',
            'youtube_url' => '',
            'tiktok_url' => '',
            'whatsapp_url' => '',
            
            // Impact statistics (these would come from actual data in a real app)
            'beneficiaries_served' => 1250,
            'projects_completed' => 45,
            'counties_reached' => 12,
            'years_active' => date('Y') - 2024 + 1,
        ];

        $settings = [];
        foreach ($defaults as $key => $defaultValue) {
            $settings[$key] = $dbSettings[$key] ?? $defaultValue;
            
            // Handle JSON fields
            if (in_array($key, ['homepage_images']) && is_string($settings[$key])) {
                $settings[$key] = json_decode($settings[$key], true) ?: $defaultValue;
            }
        }

        return $settings;
    }
}