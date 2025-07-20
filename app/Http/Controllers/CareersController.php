<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class CareersController extends Controller
{
    /**
     * Display careers page with listings.
     */
    public function index(): Response
    {
        $careers = DB::table('career_listings')
            ->where('is_active', true)
            ->where(function ($query) {
                $query->whereNull('deadline')
                    ->orWhere('deadline', '>=', now());
            })
            ->orderBy('sort_order')
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        
        $settings = $this->getHomeSettings();
        
        return Inertia::render('Careers/Index', [
            'settings' => $settings,
            'careers' => $careers,
        ]);
    }
    
    /**
     * Submit job application.
     */
    public function apply(Request $request)
    {
        $validated = $request->validate([
            'career_listing_id' => 'required|exists:career_listings,id',
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'cover_letter' => 'nullable|string|max:5000',
            'cv' => 'required|file|mimes:pdf,doc,docx|max:5120', // 5MB max
        ]);
        
        // Check if already applied
        $existingApplication = DB::table('career_applications')
            ->where('career_listing_id', $validated['career_listing_id'])
            ->where('email', $validated['email'])
            ->first();
            
        if ($existingApplication) {
            return back()->withErrors(['email' => 'You have already applied for this position.']);
        }
        
        // Store CV
        $cvPath = $request->file('cv')->store('applications/cvs', 'local');
        
        // Create application
        DB::table('career_applications')->insert([
            'career_listing_id' => $validated['career_listing_id'],
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'cover_letter' => $validated['cover_letter'],
            'cv_path' => $cvPath,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        
        // TODO: Send confirmation email to applicant
        // TODO: Send notification to HR
        
        return redirect()->route('careers')
            ->with('success', 'Your application has been submitted successfully. We will contact you soon!');
    }
    
    /**
     * Get homepage settings from database.
     */
    private function getHomeSettings(): array
    {
        try {
            $dbSettings = DB::table('site_settings')->pluck('value', 'key')->toArray();
        } catch (\Exception $e) {
            $dbSettings = [];
        }
        
        $defaults = [
            'organization_name' => 'Ustawi Wa Jamii',
            'contact_email' => 'info@ustawiwajamii.org',
            'contact_phone' => '+254 700 000 000',
        ];

        $settings = [];
        foreach ($defaults as $key => $defaultValue) {
            $settings[$key] = $dbSettings[$key] ?? $defaultValue;
        }

        return $settings;
    }
}