<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class CareersController extends Controller
{
    /**
     * Display careers dashboard with listings and applications.
     */
    public function index(Request $request): Response
    {
        $activeTab = $request->get('tab', 'listings');
        
        // Get job listings
        $listings = DB::table('career_listings')
            ->orderBy('sort_order')
            ->orderBy('created_at', 'desc')
            ->paginate(10, ['*'], 'listings_page');
        
        // Get recent applications
        $applications = DB::table('career_applications')
            ->join('career_listings', 'career_applications.career_listing_id', '=', 'career_listings.id')
            ->select(
                'career_applications.*',
                'career_listings.title as job_title',
                'career_listings.type as job_type'
            )
            ->orderBy('career_applications.created_at', 'desc')
            ->paginate(10, ['*'], 'applications_page');
        
        // Get statistics
        $stats = [
            'total_listings' => DB::table('career_listings')->count(),
            'active_listings' => DB::table('career_listings')->where('is_active', true)->count(),
            'total_applications' => DB::table('career_applications')->count(),
            'pending_applications' => DB::table('career_applications')->where('status', 'pending')->count(),
        ];
        
        return Inertia::render('Admin/Careers/Index', [
            'listings' => $listings,
            'applications' => $applications,
            'stats' => $stats,
            'activeTab' => $activeTab,
        ]);
    }
    
    /**
     * Show form to create new career listing.
     */
    public function create(): Response
    {
        return Inertia::render('Admin/Careers/Create');
    }
    
    /**
     * Store new career listing.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'type' => 'required|in:job,volunteer',
            'department' => 'nullable|string|max:255',
            'location' => 'required|string|max:255',
            'employment_type' => 'required|in:full-time,part-time,contract,volunteer',
            'description' => 'required|string',
            'requirements' => 'required|string',
            'responsibilities' => 'required|string',
            'salary_range' => 'nullable|string|max:255',
            'deadline' => 'nullable|date|after:today',
            'is_active' => 'boolean',
        ]);
        
        DB::table('career_listings')->insert([
            'title' => $validated['title'],
            'type' => $validated['type'],
            'department' => $validated['department'],
            'location' => $validated['location'],
            'employment_type' => $validated['employment_type'],
            'description' => $validated['description'],
            'requirements' => $validated['requirements'],
            'responsibilities' => $validated['responsibilities'],
            'salary_range' => $validated['salary_range'],
            'deadline' => $validated['deadline'],
            'is_active' => $validated['is_active'] ?? true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        
        return redirect()->route('admin.careers.index')
            ->with('success', 'Career listing created successfully.');
    }
    
    /**
     * Show form to edit career listing.
     */
    public function edit($id): Response
    {
        $listing = DB::table('career_listings')->where('id', $id)->first();
        
        if (!$listing) {
            abort(404);
        }
        
        return Inertia::render('Admin/Careers/Edit', [
            'listing' => $listing,
        ]);
    }
    
    /**
     * Update career listing.
     */
    public function update(Request $request, $id)
    {
        $listing = DB::table('career_listings')->where('id', $id)->first();
        
        if (!$listing) {
            abort(404);
        }
        
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'type' => 'required|in:job,volunteer',
            'department' => 'nullable|string|max:255',
            'location' => 'required|string|max:255',
            'employment_type' => 'required|in:full-time,part-time,contract,volunteer',
            'description' => 'required|string',
            'requirements' => 'required|string',
            'responsibilities' => 'required|string',
            'salary_range' => 'nullable|string|max:255',
            'deadline' => 'nullable|date',
            'is_active' => 'boolean',
            'sort_order' => 'nullable|integer',
        ]);
        
        DB::table('career_listings')->where('id', $id)->update([
            'title' => $validated['title'],
            'type' => $validated['type'],
            'department' => $validated['department'],
            'location' => $validated['location'],
            'employment_type' => $validated['employment_type'],
            'description' => $validated['description'],
            'requirements' => $validated['requirements'],
            'responsibilities' => $validated['responsibilities'],
            'salary_range' => $validated['salary_range'],
            'deadline' => $validated['deadline'],
            'is_active' => $validated['is_active'] ?? true,
            'sort_order' => $validated['sort_order'] ?? 0,
            'updated_at' => now(),
        ]);
        
        return redirect()->route('admin.careers.index')
            ->with('success', 'Career listing updated successfully.');
    }
    
    /**
     * Delete career listing.
     */
    public function destroy($id)
    {
        DB::table('career_listings')->where('id', $id)->delete();
        
        return redirect()->route('admin.careers.index')
            ->with('success', 'Career listing deleted successfully.');
    }
    
    /**
     * Toggle active status of listing.
     */
    public function toggleActive($id)
    {
        $listing = DB::table('career_listings')->where('id', $id)->first();
        
        if (!$listing) {
            abort(404);
        }
        
        DB::table('career_listings')->where('id', $id)->update([
            'is_active' => !$listing->is_active,
            'updated_at' => now(),
        ]);
        
        return back()->with('success', 'Listing status updated.');
    }
    
    /**
     * View application details.
     */
    public function viewApplication($id): Response
    {
        $application = DB::table('career_applications')
            ->join('career_listings', 'career_applications.career_listing_id', '=', 'career_listings.id')
            ->select(
                'career_applications.*',
                'career_listings.title as job_title',
                'career_listings.type as job_type',
                'career_listings.department',
                'career_listings.location'
            )
            ->where('career_applications.id', $id)
            ->first();
        
        if (!$application) {
            abort(404);
        }
        
        // Mark as reviewed
        if (!$application->reviewed_at) {
            DB::table('career_applications')->where('id', $id)->update([
                'reviewed_at' => now(),
                'reviewed_by' => auth()->id(),
            ]);
        }
        
        return Inertia::render('Admin/Careers/ViewApplication', [
            'application' => $application,
        ]);
    }
    
    /**
     * Update application status.
     */
    public function updateApplicationStatus(Request $request, $id)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,reviewing,shortlisted,rejected,hired',
            'notes' => 'nullable|string|max:1000',
        ]);
        
        DB::table('career_applications')->where('id', $id)->update([
            'status' => $validated['status'],
            'notes' => $validated['notes'],
            'updated_at' => now(),
        ]);
        
        // TODO: Send email notification to applicant based on status
        
        return back()->with('success', 'Application status updated.');
    }
    
    /**
     * Download applicant CV.
     */
    public function downloadCV($id)
    {
        $application = DB::table('career_applications')->where('id', $id)->first();
        
        if (!$application || !Storage::disk('local')->exists($application->cv_path)) {
            abort(404, 'CV not found');
        }
        
        return Storage::disk('local')->download($application->cv_path);
    }
}