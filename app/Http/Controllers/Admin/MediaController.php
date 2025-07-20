<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class MediaController extends Controller
{
    /**
     * Display the media library.
     */
    public function index(Request $request)
    {
        $query = \DB::table('media_files');
        
        // Filter by type
        if ($request->filled('type')) {
            if ($request->type === 'image') {
                $query->where('mime_type', 'like', 'image/%');
            } elseif ($request->type === 'video') {
                $query->where('mime_type', 'like', 'video/%');
            } elseif ($request->type === 'document') {
                $query->where('mime_type', 'not like', 'image/%')
                      ->where('mime_type', 'not like', 'video/%');
            }
        }
        
        // Search
        if ($request->filled('search')) {
            $search = '%' . $request->search . '%';
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', $search)
                    ->orWhere('original_name', 'like', $search)
                    ->orWhere('alt_text', 'like', $search);
            });
        }
        
        $media = $query->orderBy('created_at', 'desc')
                      ->paginate(24)
                      ->withQueryString();
        
        // Add URL to each media item
        $media->through(function ($item) {
            $item->url = Storage::url($item->file_path);
            $item->thumbnail_url = $item->thumbnail_path ? Storage::url($item->thumbnail_path) : null;
            
            // Add debugging info
            \Log::info('Media URL Debug', [
                'file_path' => $item->file_path,
                'generated_url' => $item->url,
                'full_path' => storage_path('app/public/' . $item->file_path),
                'file_exists' => file_exists(storage_path('app/public/' . $item->file_path))
            ]);
            
            return $item;
        });
        
        return Inertia::render('Admin/Media/Index', [
            'media' => $media,
            'filters' => $request->only(['type', 'search']),
        ]);
    }
    
    /**
     * Upload new media files.
     */
    public function upload(Request $request)
    {
        $request->validate([
            'files.*' => 'required|file|mimes:jpeg,jpg,png,gif,webp,mp4,webm,ogg,pdf,doc,docx|max:10240', // 10MB max
        ]);
        
        $uploadedFiles = [];
        $manager = new ImageManager(new Driver());
        
        foreach ($request->file('files') as $file) {
            try {
                // Generate unique filename
                $originalName = $file->getClientOriginalName();
                $extension = $file->getClientOriginalExtension();
                
                // Sanitize filename - remove any existing extensions and special chars
                $baseFilename = pathinfo($originalName, PATHINFO_FILENAME);
                $baseFilename = preg_replace('/[^a-zA-Z0-9-_]/', '-', $baseFilename);
                $baseFilename = Str::slug($baseFilename);
                
                $filename = $baseFilename . '-' . uniqid() . '.' . $extension;
                
                // Determine folder based on file type
                $mimeType = $file->getMimeType();
                if (str_starts_with($mimeType, 'image/')) {
                    $folder = 'images';
                } elseif (str_starts_with($mimeType, 'video/')) {
                    $folder = 'videos';
                } else {
                    $folder = 'documents';
                }
                
                $dimensions = null;
                $thumbnailPath = null;
                
                // Process images with optimization and resizing
                if (str_starts_with($mimeType, 'image/')) {
                    $image = $manager->read($file->getRealPath());
                    $originalWidth = $image->width();
                    $originalHeight = $image->height();
                    $dimensions = ['width' => $originalWidth, 'height' => $originalHeight];
                    
                    // Optimize and resize if too large
                    $maxWidth = 1920;
                    $maxHeight = 1080;
                    
                    if ($originalWidth > $maxWidth || $originalHeight > $maxHeight) {
                        $image->scaleDown($maxWidth, $maxHeight);
                    }
                    
                    // Ensure directory exists
                    Storage::disk('public')->makeDirectory('media/' . $folder);
                    
                    // Save optimized image in original format
                    $optimizedPath = storage_path('app/public/media/' . $folder . '/' . $filename);
                    
                    // Save based on original format
                    if (str_contains($mimeType, 'jpeg') || str_contains($mimeType, 'jpg')) {
                        $image->toJpeg(85)->save($optimizedPath);
                    } elseif (str_contains($mimeType, 'png')) {
                        $image->toPng()->save($optimizedPath);
                    } elseif (str_contains($mimeType, 'gif')) {
                        $image->toGif()->save($optimizedPath);
                    } elseif (str_contains($mimeType, 'webp')) {
                        $image->toWebp(85)->save($optimizedPath);
                    } else {
                        // Default to JPEG for unknown formats
                        $image->toJpeg(85)->save($optimizedPath);
                    }
                    
                    $path = 'media/' . $folder . '/' . $filename;
                    
                    // Create thumbnail for images
                    $thumbnailFilename = 'thumb_' . $filename;
                    $thumbnailImage = $manager->read($optimizedPath);
                    $thumbnailImage->scaleDown(300, 300);
                    $thumbnailPath = 'media/' . $folder . '/thumbnails/' . $thumbnailFilename;
                    
                    // Ensure thumbnail directory exists
                    Storage::disk('public')->makeDirectory('media/' . $folder . '/thumbnails');
                    
                    // Save thumbnail in same format as original
                    $thumbnailFullPath = storage_path('app/public/' . $thumbnailPath);
                    if (str_contains($mimeType, 'jpeg') || str_contains($mimeType, 'jpg')) {
                        $thumbnailImage->toJpeg(85)->save($thumbnailFullPath);
                    } elseif (str_contains($mimeType, 'png')) {
                        $thumbnailImage->toPng()->save($thumbnailFullPath);
                    } elseif (str_contains($mimeType, 'gif')) {
                        $thumbnailImage->toGif()->save($thumbnailFullPath);
                    } elseif (str_contains($mimeType, 'webp')) {
                        $thumbnailImage->toWebp(85)->save($thumbnailFullPath);
                    } else {
                        $thumbnailImage->toJpeg(85)->save($thumbnailFullPath);
                    }
                    
                } else {
                    // For non-images, store normally
                    $path = $file->storeAs('media/' . $folder, $filename, 'public');
                    
                    // Get file size
                    $fileSize = $file->getSize();
                }
                
                // Save to database
                $mediaId = \DB::table('media_files')->insertGetId([
                    'name' => $filename,
                    'original_name' => $originalName,
                    'file_path' => $path,
                    'thumbnail_path' => $thumbnailPath,
                    'mime_type' => $mimeType,
                    'extension' => $extension,
                    'file_size' => isset($fileSize) ? $fileSize : filesize(storage_path('app/public/' . $path)),
                    'dimensions' => $dimensions ? json_encode($dimensions) : null,
                    'uploaded_by' => auth()->id(),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                
                $uploadedFiles[] = [
                    'id' => $mediaId,
                    'name' => $filename,
                    'url' => Storage::url($path),
                    'thumbnail_url' => $thumbnailPath ? Storage::url($thumbnailPath) : null,
                    'mime_type' => $mimeType,
                    'dimensions' => $dimensions,
                ];
                
            } catch (\Exception $e) {
                \Log::error('Media upload failed: ' . $e->getMessage(), [
                    'file' => $originalName,
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
                continue; // Skip this file and continue with others
            }
        }
        
        return response()->json([
            'success' => true,
            'files' => $uploadedFiles,
            'message' => count($uploadedFiles) . ' file(s) uploaded successfully.',
        ]);
    }
    
    /**
     * Update media details.
     */
    public function update(Request $request, $id)
    {
        $media = \DB::table('media_files')->where('id', $id)->first();
        
        if (!$media) {
            abort(404);
        }
        
        $validated = $request->validate([
            'alt_text' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:500',
        ]);
        
        \DB::table('media_files')->where('id', $id)->update([
            'alt_text' => $validated['alt_text'],
            'description' => $validated['description'],
            'updated_at' => now(),
        ]);
        
        return redirect()->back()->with('success', 'Media updated successfully.');
    }
    
    /**
     * Delete media file.
     */
    public function destroy($id)
    {
        $media = \DB::table('media_files')->where('id', $id)->first();
        
        if (!$media) {
            abort(404);
        }
        
        // Delete file from storage
        Storage::disk('public')->delete($media->file_path);
        
        // Delete from database
        \DB::table('media_files')->where('id', $id)->delete();
        
        return redirect()->back()->with('success', 'Media deleted successfully.');
    }
    
    /**
     * Get all media for selection (used in modals).
     */
    public function all()
    {
        $media = \DB::table('media_files')
            ->orderBy('created_at', 'desc')
            ->get();
        
        // Add URL to each media item
        $media->transform(function ($item) {
            $item->url = Storage::url($item->file_path);
            $item->thumbnail_url = $item->thumbnail_path ? Storage::url($item->thumbnail_path) : null;
            return $item;
        });
        
        return response()->json($media);
    }
}