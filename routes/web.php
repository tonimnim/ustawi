<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\HomeController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/programs', [HomeController::class, 'programs'])->name('programs');

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

// Debug route for homepage images
Route::get('/debug/homepage-images', function () {
    $settings = \DB::table('site_settings')
        ->where('key', 'homepage_images')
        ->first();
    
    $mediaFiles = \DB::table('media_files')
        ->select('id', 'name', 'file_path', 'mime_type', 'alt_text')
        ->where('mime_type', 'like', 'image/%')
        ->get();
    
    return response()->json([
        'homepage_images_setting' => $settings,
        'decoded_images' => $settings ? json_decode($settings->value, true) : null,
        'all_media_files' => $mediaFiles,
        'storage_path' => storage_path('app/public'),
        'public_path' => public_path('storage'),
        'symlink_exists' => file_exists(public_path('storage')),
    ]);
});

// Include admin routes
require __DIR__.'/admin.php';
