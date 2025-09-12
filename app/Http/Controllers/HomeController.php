<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Support\Str;
use Illuminate\Http\RedirectResponse;

class HomeController extends Controller
{
    /**
     * Display the homepage.
     */
    public function index(): Response
    {
        // Get homepage settings including images
        $settings = $this->getHomeSettings();
        
        // Get latest published blog posts
        $latestPosts = \DB::table('posts')
            ->join('post_categories', 'posts.category_id', '=', 'post_categories.id')
            ->join('users', 'posts.author_id', '=', 'users.id')
            ->select(
                'posts.id',
                'posts.title',
                'posts.slug',
                'posts.excerpt',
                'posts.featured_image',
                'posts.published_at',
                'posts.views_count',
                'post_categories.name as category_name',
                'post_categories.color as category_color',
                'users.name as author_name'
            )
            ->where('posts.status', 'published')
            ->whereNotNull('posts.published_at')
            ->where('posts.published_at', '<=', now())
            ->orderBy('posts.published_at', 'desc')
            ->limit(4)
            ->get();
        
        return Inertia::render('Homepage/Index', [
            'settings' => $settings,
            'latestPosts' => $latestPosts,
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
     * Display the contact page.
     */
    public function contact(): Response
    {
        $settings = $this->getHomeSettings();
        
        return Inertia::render('Contact/Index', [
            'settings' => $settings,
        ]);
    }

    /**
     * Handle contact form submission.
     */
    public function submitContact(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|max:5000',
        ]);

        // Store the contact submission
        \DB::table('contact_submissions')->insert([
            'first_name' => $validated['first_name'],
            'last_name' => $validated['last_name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'subject' => $validated['subject'],
            'message' => $validated['message'],
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // TODO: Send email notification to admin
        
        return redirect()->route('contact')
            ->with('success', 'Thank you for contacting us! We will get back to you soon.');
    }

    /**
     * Display the donation page.
     */
    public function donate(): Response
    {
        $settings = $this->getHomeSettings();
        
        return Inertia::render('Donate/Index', [
            'settings' => $settings,
        ]);
    }

    /**
     * Display the blog listing page.
     */
    public function blog(Request $request): Response
    {
        $settings = $this->getHomeSettings();
        
        $query = \DB::table('posts')
            ->join('post_categories', 'posts.category_id', '=', 'post_categories.id')
            ->join('users', 'posts.author_id', '=', 'users.id')
            ->select(
                'posts.id',
                'posts.title',
                'posts.slug',
                'posts.excerpt',
                'posts.featured_image',
                'posts.published_at',
                'posts.views_count',
                'post_categories.name as category_name',
                'post_categories.color as category_color',
                'users.name as author_name'
            )
            ->where('posts.status', 'published')
            ->whereNotNull('posts.published_at')
            ->where('posts.published_at', '<=', now());
        
        // Search functionality
        if ($request->filled('search')) {
            $search = '%' . $request->search . '%';
            $query->where(function ($q) use ($search) {
                $q->where('posts.title', 'like', $search)
                    ->orWhere('posts.excerpt', 'like', $search)
                    ->orWhere('posts.content', 'like', $search);
            });
        }
        
        // Category filter
        if ($request->filled('category')) {
            $query->where('posts.category_id', $request->category);
        }
        
        $posts = $query->orderBy('posts.published_at', 'desc')
            ->paginate(12)
            ->withQueryString();
        
        // Get categories for filter
        $categories = \DB::table('post_categories')
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->select('id', 'name')
            ->get();
        
        return Inertia::render('Blog/Index', [
            'settings' => $settings,
            'posts' => $posts,
            'categories' => $categories,
        ]);
    }

    /**
     * Display a single blog post.
     */
    public function blogPost($slug): Response
    {
        $settings = $this->getHomeSettings();
        
        $post = \DB::table('posts')
            ->join('post_categories', 'posts.category_id', '=', 'post_categories.id')
            ->join('users', 'posts.author_id', '=', 'users.id')
            ->select(
                'posts.*',
                'post_categories.name as category_name',
                'post_categories.color as category_color',
                'users.name as author_name'
            )
            ->where('posts.slug', $slug)
            ->where('posts.status', 'published')
            ->whereNotNull('posts.published_at')
            ->where('posts.published_at', '<=', now())
            ->first();
        
        if (!$post) {
            abort(404);
        }
        
        // Increment view count
        \DB::table('posts')
            ->where('id', $post->id)
            ->increment('views_count');
        
        // Get related posts
        $relatedPosts = \DB::table('posts')
            ->join('post_categories', 'posts.category_id', '=', 'post_categories.id')
            ->join('users', 'posts.author_id', '=', 'users.id')
            ->select(
                'posts.id',
                'posts.title',
                'posts.slug',
                'posts.excerpt',
                'posts.featured_image',
                'posts.published_at',
                'post_categories.name as category_name',
                'post_categories.color as category_color'
            )
            ->where('posts.category_id', $post->category_id)
            ->where('posts.id', '!=', $post->id)
            ->where('posts.status', 'published')
            ->whereNotNull('posts.published_at')
            ->where('posts.published_at', '<=', now())
            ->orderBy('posts.published_at', 'desc')
            ->limit(3)
            ->get();
        
        return Inertia::render('Blog/Show', [
            'settings' => $settings,
            'post' => $post,
            'relatedPosts' => $relatedPosts,
        ]);
    }

    /**
     * Display the gallery page.
     */
    public function gallery(): Response
    {
        $settings = $this->getHomeSettings();
        
        // Fetch gallery images from database
        $images = \DB::table('gallery_images')
            ->orderByDesc('created_at')
            ->get()
            ->map(function ($image) {
                // Ensure URLs are properly formatted for Laravel Cloud
                if (!str_starts_with($image->url, 'http')) {
                    $image->url = asset('storage/' . $image->path);
                }
                return $image;
            });
        
        return Inertia::render('Gallery/Index', [
            'settings' => $settings,
            'images' => $images,
        ]);
    }

    /**
     * Process donation.
     */
    public function processDonation(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'amount' => 'required|numeric|min:1',
            'frequency' => 'required|in:one-time,monthly',
            'payment_method' => 'required|in:card,mpesa',
            'project_designation' => 'required|string',
            'donor_name' => 'required_unless:is_anonymous,true|nullable|string|max:255',
            'donor_email' => 'required_unless:is_anonymous,true|nullable|email|max:255',
            'donor_phone' => 'required_if:payment_method,mpesa|nullable|string|max:20',
            'donor_message' => 'nullable|string|max:1000',
            'is_anonymous' => 'boolean',
        ]);

        // Generate unique donation number
        $donationNumber = 'DON-' . date('Ymd') . '-' . strtoupper(substr(md5(uniqid()), 0, 6));
        
        // Store the donation record
        $donationId = \DB::table('donations')->insertGetId([
            'donation_number' => $donationNumber,
            'amount' => $validated['amount'],
            'currency' => 'KES',
            'frequency' => $validated['frequency'],
            'payment_method' => $validated['payment_method'],
            'project_designation' => $validated['project_designation'],
            'donor_name' => ($validated['is_anonymous'] ?? false) ? 'Anonymous' : ($validated['donor_name'] ?? 'Anonymous'),
            'donor_email' => ($validated['is_anonymous'] ?? false) ? null : ($validated['donor_email'] ?? null),
            'donor_phone' => $validated['donor_phone'],
            'donor_message' => $validated['donor_message'],
            'is_anonymous' => $validated['is_anonymous'] ?? false,
            'status' => 'pending',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Redirect to Paystack payment controller
        return redirect()->route('donations.pay', ['donation_id' => $donationId]);
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
            'organization_description' => 'Empowering communities through sustainable development and youth leadership.',
            'mission_statement' => 'To empower youth and communities through sustainable development initiatives, capacity building, and innovative solutions that create lasting positive impact.',
            'contact_email' => 'info@ustawiwajamii.org',
            'contact_phone' => '+254 700 000 000',
            'physical_address' => 'Nairobi',
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