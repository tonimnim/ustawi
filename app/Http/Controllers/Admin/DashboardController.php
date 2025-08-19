<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // Get current month dates
        $startOfMonth = Carbon::now()->startOfMonth();
        $endOfMonth = Carbon::now()->endOfMonth();
        $startOfLastMonth = Carbon::now()->subMonth()->startOfMonth();
        $endOfLastMonth = Carbon::now()->subMonth()->endOfMonth();
        
        // Get donations stats
        $currentMonthDonations = DB::table('donations')
            ->where('status', 'completed')
            ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
            ->sum('amount');
            
        $lastMonthDonations = DB::table('donations')
            ->where('status', 'completed')
            ->whereBetween('created_at', [$startOfLastMonth, $endOfLastMonth])
            ->sum('amount');
            
        $donationChange = $lastMonthDonations > 0 
            ? round((($currentMonthDonations - $lastMonthDonations) / $lastMonthDonations) * 100, 1)
            : 0;
            
        // Get active donors count
        $activeDonors = DB::table('donations')
            ->where('status', 'completed')
            ->distinct('donor_email')
            ->count('donor_email');
            
        $lastMonthDonors = DB::table('donations')
            ->where('status', 'completed')
            ->whereBetween('created_at', [$startOfLastMonth, $endOfLastMonth])
            ->distinct('donor_email')
            ->count('donor_email');
            
        $donorsChange = $lastMonthDonors > 0 
            ? round((($activeDonors - $lastMonthDonors) / $lastMonthDonors) * 100, 1)
            : 0;
            
        // Get newsletter subscribers
        $totalSubscribers = DB::table('newsletter_subscribers')
            ->where('is_active', true)
            ->count();
            
        $newSubscribersThisMonth = DB::table('newsletter_subscribers')
            ->where('is_active', true)
            ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
            ->count();
            
        $subscribersLastMonth = DB::table('newsletter_subscribers')
            ->where('is_active', true)
            ->whereBetween('created_at', [$startOfLastMonth, $endOfLastMonth])
            ->count();
            
        $subscribersChange = $subscribersLastMonth > 0 
            ? round((($newSubscribersThisMonth - $subscribersLastMonth) / $subscribersLastMonth) * 100, 1)
            : 0;
            
        // Get today's visitors from access logs
        $todayVisitors = DB::table('access_logs')
            ->whereDate('created_at', Carbon::today())
            ->distinct('ip_address')
            ->count('ip_address');
            
        $yesterdayVisitors = DB::table('access_logs')
            ->whereDate('created_at', Carbon::yesterday())
            ->distinct('ip_address')
            ->count('ip_address');
            
        $visitorsChange = $yesterdayVisitors > 0 
            ? round((($todayVisitors - $yesterdayVisitors) / $yesterdayVisitors) * 100, 1)
            : 0;
            
        // Build stats array
        $stats = [
            [
                'name' => 'Total Donations (This Month)',
                'value' => 'KES ' . number_format($currentMonthDonations, 0),
                'change' => ($donationChange >= 0 ? '+' : '') . $donationChange . '%',
                'changeType' => $donationChange >= 0 ? 'increase' : 'decrease',
                'icon' => 'M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z'
            ],
            [
                'name' => 'Active Donors',
                'value' => number_format($activeDonors, 0),
                'change' => ($donorsChange >= 0 ? '+' : '') . $donorsChange . '%',
                'changeType' => $donorsChange >= 0 ? 'increase' : 'decrease',
                'icon' => 'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z'
            ],
            [
                'name' => 'Newsletter Subscribers',
                'value' => number_format($totalSubscribers, 0),
                'change' => ($subscribersChange >= 0 ? '+' : '') . $subscribersChange . '%',
                'changeType' => $subscribersChange >= 0 ? 'increase' : 'decrease',
                'icon' => 'M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 2.25l-8.8 5.28a2.25 2.25 0 01-2.4 0L2.25 9M15 10.5a3 3 0 11-6 0 3 3 0 016 0z'
            ],
            [
                'name' => 'Website Visitors (Today)',
                'value' => number_format($todayVisitors, 0),
                'change' => ($visitorsChange >= 0 ? '+' : '') . $visitorsChange . '%',
                'changeType' => $visitorsChange >= 0 ? 'increase' : 'decrease',
                'icon' => 'M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z M15 12a3 3 0 11-6 0 3 3 0 016 0z'
            ]
        ];
        
        // Get recent activities
        $recentActivities = [];
        
        // Recent donations
        $recentDonations = DB::table('donations')
            ->where('status', 'completed')
            ->orderBy('created_at', 'desc')
            ->limit(2)
            ->get();
            
        foreach ($recentDonations as $donation) {
            $recentActivities[] = [
                'id' => 'donation_' . $donation->id,
                'type' => 'donation',
                'title' => 'New donation received',
                'description' => 'KES ' . number_format($donation->amount, 0) . 
                    ($donation->donor_name && $donation->donor_name !== 'Anonymous' ? ' from ' . $donation->donor_name : '') .
                    ($donation->project_designation ? ' for ' . $donation->project_designation : ''),
                'time' => Carbon::parse($donation->created_at)->diffForHumans(),
                'icon' => 'donation'
            ];
        }
        
        // Recent job applications
        $recentApplications = DB::table('career_applications')
            ->join('career_listings', 'career_applications.career_listing_id', '=', 'career_listings.id')
            ->select('career_applications.*', 'career_listings.title as job_title')
            ->orderBy('career_applications.created_at', 'desc')
            ->limit(1)
            ->get();
            
        foreach ($recentApplications as $application) {
            $recentActivities[] = [
                'id' => 'application_' . $application->id,
                'type' => 'application',
                'title' => 'Job application submitted',
                'description' => 'Application for ' . $application->job_title,
                'time' => Carbon::parse($application->created_at)->diffForHumans(),
                'icon' => 'application'
            ];
        }
        
        // Recent blog posts
        $recentPosts = DB::table('posts')
            ->where('status', 'published')
            ->orderBy('published_at', 'desc')
            ->limit(1)
            ->get();
            
        foreach ($recentPosts as $post) {
            $recentActivities[] = [
                'id' => 'post_' . $post->id,
                'type' => 'blog',
                'title' => 'Blog post published',
                'description' => $post->title,
                'time' => Carbon::parse($post->published_at)->diffForHumans(),
                'icon' => 'blog'
            ];
        }
        
        // Recent contact submissions
        $recentContacts = DB::table('contact_submissions')
            ->orderBy('created_at', 'desc')
            ->limit(1)
            ->get();
            
        foreach ($recentContacts as $contact) {
            $recentActivities[] = [
                'id' => 'contact_' . $contact->id,
                'type' => 'contact',
                'title' => 'Contact form submission',
                'description' => $contact->subject . ' from ' . $contact->first_name . ' ' . $contact->last_name,
                'time' => Carbon::parse($contact->created_at)->diffForHumans(),
                'icon' => 'contact'
            ];
        }
        
        // Sort activities by time
        usort($recentActivities, function($a, $b) {
            return strtotime($b['time']) - strtotime($a['time']);
        });
        
        // Limit to 5 most recent
        $recentActivities = array_slice($recentActivities, 0, 5);
        
        // Get impact metrics (these would normally come from settings or a dedicated table)
        // For now, we'll calculate some basic metrics
        $totalDonations = DB::table('donations')
            ->where('status', 'completed')
            ->sum('amount');
            
        $totalPosts = DB::table('posts')
            ->where('status', 'published')
            ->count();
            
        $totalApplications = DB::table('career_applications')
            ->count();
            
        $totalContacts = DB::table('contact_submissions')
            ->count();
        
        $impactMetrics = [
            [
                'name' => 'Total Donations Raised',
                'value' => 'KES ' . number_format($totalDonations / 1000, 0) . 'K',
                'target' => 'KES 10M',
                'progress' => min(100, round(($totalDonations / 10000000) * 100))
            ],
            [
                'name' => 'Blog Posts Published',
                'value' => number_format($totalPosts, 0),
                'target' => '100',
                'progress' => min(100, round(($totalPosts / 100) * 100))
            ],
            [
                'name' => 'Job Applications',
                'value' => number_format($totalApplications, 0),
                'target' => '500',
                'progress' => min(100, round(($totalApplications / 500) * 100))
            ],
            [
                'name' => 'Community Contacts',
                'value' => number_format($totalContacts, 0),
                'target' => '1000',
                'progress' => min(100, round(($totalContacts / 1000) * 100))
            ]
        ];
        
        return Inertia::render('Dashboard', [
            'stats' => $stats,
            'recentActivities' => $recentActivities,
            'impactMetrics' => $impactMetrics
        ]);
    }
}