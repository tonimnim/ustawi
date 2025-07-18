<?php

use App\Http\Controllers\Admin\AdminProfileController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Admin\AdminUsersController;
use App\Http\Controllers\Admin\LogsController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
|
| Here is where you can register admin routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "admin" middleware group.
|
*/

// Admin routes that require authentication and admin role
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    
    // Dashboard
    Route::get('/dashboard', function () {
        return inertia('Dashboard');
    })->name('dashboard');

    // Profile Management
    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/', [AdminProfileController::class, 'show'])->name('show');
        Route::get('/edit', [AdminProfileController::class, 'edit'])->name('edit');
        Route::put('/', [AdminProfileController::class, 'update'])->name('update');
        Route::put('/password', [AdminProfileController::class, 'updatePassword'])->name('password.update');
        Route::delete('/profile-picture', [AdminProfileController::class, 'deleteProfilePicture'])->name('profile-picture.delete');
    });

    // Settings Management
    Route::prefix('settings')->name('settings.')->group(function () {
        Route::get('/', [SettingsController::class, 'index'])->name('index');
        Route::put('/{section}', [SettingsController::class, 'update'])->name('update');
    });

    // Placeholder routes for navigation items (will be implemented later)
    Route::get('/pages', function () {
        return inertia('Admin/Pages/Index');
    })->name('pages.index');

    Route::get('/posts', function () {
        return inertia('Admin/Posts/Index');
    })->name('posts.index');

    Route::get('/media', function () {
        return inertia('Admin/Media/Index');
    })->name('media.index');

    Route::get('/team', function () {
        return inertia('Admin/Team/Index');
    })->name('team.index');

    Route::get('/projects', function () {
        return inertia('Admin/Projects/Index');
    })->name('projects.index');

    Route::get('/metrics', function () {
        return inertia('Admin/Metrics/Index');
    })->name('metrics.index');

    Route::get('/stories', function () {
        return inertia('Admin/Stories/Index');
    })->name('stories.index');

    Route::get('/donations', function () {
        return inertia('Admin/Donations/Index');
    })->name('donations.index');

    Route::get('/donors', function () {
        return inertia('Admin/Donors/Index');
    })->name('donors.index');

    Route::get('/reports', function () {
        return inertia('Admin/Reports/Index');
    })->name('reports.index');

    Route::get('/payments', function () {
        return inertia('Admin/Payments/Index');
    })->name('payments.index');

    Route::get('/users', function () {
        return inertia('Admin/Users/Index');
    })->name('users.index');

    Route::get('/applications', function () {
        return inertia('Admin/Applications/Index');
    })->name('applications.index');

    Route::get('/jobs', function () {
        return inertia('Admin/Jobs/Index');
    })->name('jobs.index');

    Route::get('/newsletter', function () {
        return inertia('Admin/Newsletter/Index');
    })->name('newsletter.index');

    Route::get('/contacts', function () {
        return inertia('Admin/Contacts/Index');
    })->name('contacts.index');

    Route::get('/campaigns', function () {
        return inertia('Admin/Campaigns/Index');
    })->name('campaigns.index');

    Route::get('/comments', function () {
        return inertia('Admin/Comments/Index');
    })->name('comments.index');

    Route::get('/analytics', function () {
        return inertia('Admin/Analytics/Index');
    })->name('analytics.index');

    Route::get('/donation-analytics', function () {
        return inertia('Admin/DonationAnalytics/Index');
    })->name('donation-analytics.index');

    Route::get('/custom-reports', function () {
        return inertia('Admin/CustomReports/Index');
    })->name('custom-reports.index');


    // Admin Users Management
    Route::prefix('admin-users')->name('admin-users.')->group(function () {
        Route::get('/', [AdminUsersController::class, 'index'])->name('index');
        Route::get('/create', [AdminUsersController::class, 'create'])->name('create');
        Route::post('/', [AdminUsersController::class, 'store'])->name('store');
        Route::get('/{user}/edit', [AdminUsersController::class, 'edit'])->name('edit');
        Route::put('/{user}', [AdminUsersController::class, 'update'])->name('update');
        Route::put('/{user}/toggle-status', [AdminUsersController::class, 'toggleStatus'])->name('toggle-status');
        Route::delete('/{user}', [AdminUsersController::class, 'destroy'])->name('destroy');
    });

    // System Logs
    Route::prefix('logs')->name('logs.')->group(function () {
        Route::get('/', [LogsController::class, 'index'])->name('index');
        Route::delete('/clear-old', [LogsController::class, 'clearOldLogs'])->name('clear-old');
    });
});