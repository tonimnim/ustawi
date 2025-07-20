<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class StorageController extends Controller
{
    /**
     * Serve files from storage for Laravel Cloud compatibility
     */
    public function serve($path)
    {
        // Security check - only allow certain directories
        $allowedPrefixes = ['homepage/', 'media/', 'careers/'];
        $isAllowed = false;
        
        foreach ($allowedPrefixes as $prefix) {
            if (str_starts_with($path, $prefix)) {
                $isAllowed = true;
                break;
            }
        }
        
        if (!$isAllowed) {
            abort(403);
        }
        
        // Check if file exists in storage
        if (!Storage::disk('public')->exists($path)) {
            abort(404);
        }
        
        // Get file contents and mime type
        $file = Storage::disk('public')->get($path);
        $mimeType = Storage::disk('public')->mimeType($path);
        
        // Return file response
        return response($file, 200)
            ->header('Content-Type', $mimeType)
            ->header('Cache-Control', 'public, max-age=31536000');
    }
}