<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class GalleryController extends Controller
{
    /**
     * Display all gallery images.
     */
    public function index(): Response
    {
        $images = DB::table('gallery_images')
            ->orderByDesc('created_at')
            ->paginate(20);

        return Inertia::render('Admin/Gallery/Index', [
            'images' => $images,
        ]);
    }

    /**
     * Store a newly uploaded image.
     */
    public function store(Request $request): RedirectResponse
    {
        // Increase execution time for large uploads
        set_time_limit(120); // 120 seconds (2 minutes)
        
        $validated = $request->validate([
            'images' => 'required|array',
            'images.*' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:20480', // 20MB max per image
        ]);

        try {
            foreach ($request->file('images') as $uploadedFile) {
                // Generate unique filename
                $filename = Str::uuid() . '.' . $uploadedFile->getClientOriginalExtension();
                
                // Store in public disk under gallery folder
                $path = $uploadedFile->storeAs('gallery', $filename, 'public');
                
                // Generate URL like homepage images use /media/ prefix
                $url = '/media/' . $path;
                
                // Store in database
                DB::table('gallery_images')->insert([
                    'filename' => $filename,
                    'path' => $path,
                    'url' => $url,
                    'size' => $uploadedFile->getSize(),
                    'mime_type' => $uploadedFile->getMimeType(),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            return redirect()->route('admin.gallery.index')
                ->with('success', 'Images uploaded successfully!');
                
        } catch (\Exception $e) {
            Log::error('Gallery upload error: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Failed to upload images. Please try again.');
        }
    }

    /**
     * Delete an image from gallery.
     */
    public function destroy($id): RedirectResponse
    {
        $image = DB::table('gallery_images')->find($id);

        if (!$image) {
            abort(404);
        }

        try {
            // Delete from storage
            if (Storage::disk('public')->exists($image->path)) {
                Storage::disk('public')->delete($image->path);
            }

            // Delete from database
            DB::table('gallery_images')->where('id', $id)->delete();

            return redirect()->route('admin.gallery.index')
                ->with('success', 'Image deleted successfully!');
                
        } catch (\Exception $e) {
            Log::error('Gallery delete error: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Failed to delete image. Please try again.');
        }
    }
}