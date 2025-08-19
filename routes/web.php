<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\CareersController;
use App\Http\Controllers\NewsletterController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/programs', [HomeController::class, 'programs'])->name('programs');
Route::get('/contact', [HomeController::class, 'contact'])->name('contact');
Route::post('/contact', [HomeController::class, 'submitContact'])->name('contact.store');
Route::get('/donate', [HomeController::class, 'donate'])->name('donate');
Route::post('/donate', [HomeController::class, 'processDonation'])->name('donate.process');

// Paystack payment routes
Route::get('/donations/pay/{donation_id}', [\App\Http\Controllers\PaystackController::class, 'initializePayment'])->name('donations.pay');
Route::get('/donations/callback', [\App\Http\Controllers\PaystackController::class, 'handleCallback'])->name('donations.callback');
Route::post('/paystack/webhook', [\App\Http\Controllers\PaystackController::class, 'webhook'])->name('paystack.webhook');
Route::get('/donations/status/{donation_number}', [\App\Http\Controllers\DonationStatusController::class, 'checkStatus'])->name('donations.status');

// Blog routes
Route::get('/blog', [HomeController::class, 'blog'])->name('blog');
Route::get('/blog/{slug}', [HomeController::class, 'blogPost'])->name('blog.show');

// Careers
Route::get('/careers', [CareersController::class, 'index'])->name('careers');
Route::post('/careers/apply', [CareersController::class, 'apply'])->name('careers.apply');

// Newsletter routes
Route::post('/newsletter/subscribe', [NewsletterController::class, 'subscribe'])->name('newsletter.subscribe');
Route::get('/newsletter/unsubscribe/{token}', [NewsletterController::class, 'unsubscribe'])->name('newsletter.unsubscribe');

// Redirect /dashboard to admin dashboard for authenticated users
Route::get('/dashboard', function () {
    if (auth()->check() && auth()->user()->isAdmin()) {
        return redirect()->route('admin.dashboard');
    }
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

// Include admin routes
require __DIR__.'/admin.php';

// Media file serving route for Laravel Cloud (avoiding /storage which nginx handles)
Route::get('/media/{path}', [\App\Http\Controllers\StorageController::class, 'serve'])
    ->where('path', '.*')
    ->name('media.serve');
