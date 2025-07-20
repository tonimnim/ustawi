<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class SettingsController extends Controller
{
    /**
     * Display the settings page.
     */
    public function index(): Response
    {
        // Get all settings from database or return defaults
        $settings = $this->getSettings();
        
        return Inertia::render('Admin/Settings/Index', [
            'settings' => $settings,
            'counties' => $this->getKenyanCounties(),
        ]);
    }

    /**
     * Update specific settings section.
     */
    public function update(Request $request, string $section)
    {
        switch ($section) {
            case 'organization':
                return $this->updateOrganizationSettings($request);
            case 'donations':
                return $this->updateDonationSettings($request);
            case 'email':
                return $this->updateEmailSettings($request);
            case 'social':
                return $this->updateSocialSettings($request);
            case 'homepage':
                return $this->updateHomepageSettings($request);
            default:
                return back()->withErrors(['section' => 'Invalid settings section']);
        }
    }

    /**
     * Update organization settings.
     */
    private function updateOrganizationSettings(Request $request)
    {
        $validated = $request->validate([
            'organization_name' => 'required|string|max:255',
            'organization_description' => 'nullable|string|max:1000',
            'contact_email' => 'required|email|max:255',
            'contact_phone' => 'nullable|string|max:20',
            'physical_address' => 'nullable|string|max:500',
            'mission_statement' => 'nullable|string|max:1000',
        ]);

        foreach ($validated as $key => $value) {
            $this->updateSetting($key, $value);
        }

        return back()->with('success', 'Organization settings updated successfully.');
    }

    /**
     * Update donation settings.
     */
    private function updateDonationSettings(Request $request)
    {
        $validated = $request->validate([
            'mpesa_paybill' => 'required|string|max:10',
            'default_amounts' => 'required|array|min:1',
            'default_amounts.*' => 'required|integer|min:1',
            'receipt_email_template' => 'nullable|string',
        ]);

        foreach ($validated as $key => $value) {
            $this->updateSetting($key, $value);
        }

        return back()->with('success', 'Donation settings updated successfully.');
    }

    /**
     * Update email settings.
     */
    private function updateEmailSettings(Request $request)
    {
        $validated = $request->validate([
            'from_email' => 'required|email|max:255',
            'contact_form_recipient' => 'required|email|max:255',
            'admin_email' => 'required|email|max:255',
            'support_email' => 'nullable|email|max:255',
            'donations_email' => 'nullable|email|max:255',
            'partnerships_email' => 'nullable|email|max:255',
            'email_signature' => 'nullable|string|max:1000',
            'smtp_host' => 'nullable|string|max:255',
            'smtp_port' => 'nullable|integer|min:1|max:65535',
            'smtp_username' => 'nullable|string|max:255',
            'smtp_password' => 'nullable|string|max:255',
            'smtp_encryption' => 'nullable|string|in:tls,ssl,none',
        ]);

        foreach ($validated as $key => $value) {
            $this->updateSetting($key, $value);
        }

        return back()->with('success', 'Email settings updated successfully.');
    }

    /**
     * Update social media settings.
     */
    private function updateSocialSettings(Request $request)
    {
        $validated = $request->validate([
            'facebook_url' => 'nullable|url|max:255',
            'twitter_url' => 'nullable|url|max:255',
            'instagram_url' => 'nullable|url|max:255',
            'linkedin_url' => 'nullable|url|max:255',
            'youtube_url' => 'nullable|url|max:255',
            'tiktok_url' => 'nullable|url|max:255',
            'whatsapp_url' => 'nullable|url|max:255',
        ]);

        foreach ($validated as $key => $value) {
            $this->updateSetting($key, $value);
        }

        return back()->with('success', 'Social media settings updated successfully.');
    }

    /**
     * Update homepage images.
     */
    private function updateHomepageSettings(Request $request)
    {
        try {
            $request->validate([
                'images' => 'nullable|array',
                'images.*' => 'image|mimes:jpeg,png,jpg|max:2048',
                'existing_images' => 'nullable|string',
            ]);

            $uploadedImages = [];
            
            // Handle existing images to keep
            $existingImages = [];
            if ($request->has('existing_images')) {
                $existingImagesRaw = json_decode($request->existing_images, true);
                $existingImages = is_array($existingImagesRaw) ? $existingImagesRaw : [];
            }

        // Process new image uploads
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $index => $file) {
                if ($file && $file->isValid()) {
                    // Generate unique filename
                    $filename = time() . '_' . $index . '_' . str_replace(' ', '_', $file->getClientOriginalName());
                    
                    // Store in public/storage/homepage directory
                    $path = $file->storeAs('homepage', $filename, 'public');
                    
                    $uploadedImages[] = [
                        'id' => uniqid() . '_' . time(),
                        'filename' => $filename,
                        'path' => $path,
                        'original_name' => $file->getClientOriginalName(),
                        'uploaded_at' => now()->toISOString(),
                    ];
                }
            }
        }

        // Get current homepage images
        $currentImages = [];
        try {
            $currentImagesJson = \DB::table('site_settings')
                ->where('key', 'homepage_images')
                ->value('value');
            if ($currentImagesJson) {
                $currentImages = json_decode($currentImagesJson, true) ?: [];
            }
        } catch (\Exception $e) {
            // If table doesn't exist or error, start with empty array
            $currentImages = [];
        }

        // Filter current images to keep only existing ones
        $filteredCurrentImages = [];
        if (!empty($existingImages) && !empty($currentImages)) {
            $filteredCurrentImages = array_filter($currentImages, function($image) use ($existingImages) {
                return isset($image['id']) && in_array($image['id'], $existingImages);
            });
        }

        // Combine existing and new images
        $allImages = array_merge(array_values($filteredCurrentImages), $uploadedImages);
        
        // Enforce maximum of 4 images
        if (count($allImages) > 4) {
            // Clean up any uploaded images if limit exceeded
            foreach ($uploadedImages as $uploaded) {
                $filePath = storage_path('app/public/' . $uploaded['path']);
                if (file_exists($filePath)) {
                    unlink($filePath);
                }
            }
            return back()->withErrors(['images' => 'Maximum 4 images allowed on homepage.']);
        }

        // Save updated images list
        $this->updateSetting('homepage_images', $allImages);

        // Clean up removed image files
        if (!empty($currentImages)) {
            foreach ($currentImages as $currentImage) {
                if (isset($currentImage['id']) && !in_array($currentImage['id'], $existingImages)) {
                    // Delete the file from storage
                    if (isset($currentImage['path'])) {
                        $filePath = storage_path('app/public/' . $currentImage['path']);
                        if (file_exists($filePath)) {
                            unlink($filePath);
                        }
                    } elseif (isset($currentImage['filename'])) {
                        // Fallback to filename if path not set
                        $filePath = storage_path('app/public/homepage/' . $currentImage['filename']);
                        if (file_exists($filePath)) {
                            unlink($filePath);
                        }
                    }
                }
            }
        }

        $message = count($uploadedImages) > 0 
            ? 'Homepage images uploaded successfully.' 
            : 'Homepage images updated successfully.';

        return back()->with('success', $message);
        
        } catch (\Exception $e) {
            \Log::error('Homepage settings update error: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Failed to update homepage settings: ' . $e->getMessage()]);
        }
    }


    /**
     * Get all settings from database or return defaults.
     */
    private function getSettings(): array
    {
        // This would typically fetch from a settings table
        // For now, return defaults with any existing values
        $settings = [];
        
        // Try to get settings from database, handle if table doesn't exist
        try {
            $dbSettings = \DB::table('site_settings')->pluck('value', 'key')->toArray();
        } catch (\Exception $e) {
            // If table doesn't exist or other error, use empty array
            $dbSettings = [];
        }
        
        // Default settings with database overrides
        $defaults = [
            'organization_name' => 'Ustawi Wa Jamii',
            'organization_description' => 'Empowering communities through sustainable development and youth leadership across Kenya.',
            'contact_email' => 'info@ustawiwajamii.org',
            'contact_phone' => '+254 700 000 000',
            'physical_address' => 'Nairobi, Kenya',
            'mission_statement' => 'To empower youth and communities through sustainable development initiatives, capacity building, and innovative solutions that create lasting positive impact across Kenya.',
            'mpesa_paybill' => '',
            'default_amounts' => [500, 1000, 2500, 5000],
            'receipt_email_template' => "Dear {donor_name},\n\nThank you for your generous donation of KES {amount} to Ustawi Wa Jamii.\n\nYour support helps us continue our mission of empowering communities through sustainable development.\n\nBest regards,\nUstawi Wa Jamii Team",
            'from_email' => 'noreply@ustawiwajamii.org',
            'contact_form_recipient' => 'info@ustawiwajamii.org',
            'admin_email' => 'admin@ustawiwajamii.org',
            'support_email' => 'support@ustawiwajamii.org',
            'donations_email' => 'donations@ustawiwajamii.org',
            'partnerships_email' => 'partnerships@ustawiwajamii.org',
            'email_signature' => "Best regards,\nUstawi Wa Jamii Team\nEmail: info@ustawiwajamii.org\nWebsite: www.ustawiwajamii.org",
            'smtp_host' => '',
            'smtp_port' => 587,
            'smtp_username' => '',
            'smtp_password' => '',
            'smtp_encryption' => 'tls',
            'facebook_url' => '',
            'twitter_url' => '',
            'instagram_url' => '',
            'linkedin_url' => '',
            'youtube_url' => '',
            'tiktok_url' => '',
            'whatsapp_url' => '',
            'homepage_images' => [],
        ];

        foreach ($defaults as $key => $defaultValue) {
            $settings[$key] = $dbSettings[$key] ?? $defaultValue;
            
            // Handle JSON fields
            if (in_array($key, ['default_amounts', 'homepage_images']) && is_string($settings[$key])) {
                $settings[$key] = json_decode($settings[$key], true) ?: $defaultValue;
            }
        }

        return $settings;
    }

    /**
     * Update a single setting in the database.
     */
    private function updateSetting(string $key, $value): void
    {
        try {
            // Convert arrays to JSON
            $originalValue = $value;
            if (is_array($value)) {
                $value = json_encode($value);
            }

            \DB::table('site_settings')->updateOrInsert(
                ['key' => $key],
                [
                    'value' => $value,
                    'type' => is_array($originalValue) ? 'json' : (is_numeric($value) ? 'number' : 'text'),
                    'group' => $this->getSettingGroup($key),
                    'updated_at' => now(),
                    'created_at' => now(),
                ]
            );
        } catch (\Exception $e) {
            // If there's an error (like table doesn't exist), log it but don't fail
            \Log::warning("Could not update setting {$key}: " . $e->getMessage());
        }
    }

    /**
     * Get the group for a setting key.
     */
    private function getSettingGroup(string $key): string
    {
        $groups = [
            'organization_name' => 'organization',
            'organization_description' => 'organization',
            'contact_email' => 'organization',
            'contact_phone' => 'organization',
            'physical_address' => 'organization',
            'mission_statement' => 'organization',
            'mpesa_paybill' => 'donations',
            'default_amounts' => 'donations',
            'receipt_email_template' => 'donations',
            'from_email' => 'email',
            'contact_form_recipient' => 'email',
            'admin_email' => 'email',
            'support_email' => 'email',
            'donations_email' => 'email',
            'partnerships_email' => 'email',
            'email_signature' => 'email',
            'smtp_host' => 'email',
            'smtp_port' => 'email',
            'smtp_username' => 'email',
            'smtp_password' => 'email',
            'smtp_encryption' => 'email',
            'facebook_url' => 'social',
            'twitter_url' => 'social',
            'instagram_url' => 'social',
            'linkedin_url' => 'social',
            'youtube_url' => 'social',
            'tiktok_url' => 'social',
            'whatsapp_url' => 'social',
            'homepage_images' => 'homepage',
        ];

        return $groups[$key] ?? 'general';
    }

    /**
     * Get list of Kenyan counties.
     */
    private function getKenyanCounties(): array
    {
        return [
            'Baringo', 'Bomet', 'Bungoma', 'Busia', 'Elgeyo-Marakwet', 'Embu',
            'Garissa', 'Homa Bay', 'Isiolo', 'Kajiado', 'Kakamega', 'Kericho',
            'Kiambu', 'Kilifi', 'Kirinyaga', 'Kisii', 'Kisumu', 'Kitui',
            'Kwale', 'Laikipia', 'Lamu', 'Machakos', 'Makueni', 'Mandera',
            'Marsabit', 'Meru', 'Migori', 'Mombasa', 'Murang\'a', 'Nairobi',
            'Nakuru', 'Nandi', 'Narok', 'Nyamira', 'Nyandarua', 'Nyeri',
            'Samburu', 'Siaya', 'Taita-Taveta', 'Tana River', 'Tharaka-Nithi',
            'Trans Nzoia', 'Turkana', 'Uasin Gishu', 'Vihiga', 'Wajir', 'West Pokot'
        ];
    }
}