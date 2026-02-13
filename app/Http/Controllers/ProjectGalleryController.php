<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\ProjectGallery;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProjectGalleryController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Client $client)
    {
        try {
            \Illuminate\Support\Facades\Log::info('Upload Request', $request->all());

            // Check if file exists in request BEFORE validation
            if (!$request->hasFile('image')) {
                return back()->with('error', 'No file detected. Check file size limits (upload_max_filesize).');
            }

            $file = $request->file('image');
            if (!$file->isValid()) {
                \Illuminate\Support\Facades\Log::error('Upload Failed', ['error' => $file->getErrorMessage()]);
                return back()->with('error', 'Upload failed: ' . $file->getErrorMessage());
            }

            $request->validate([
                'image' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:10240', // 10MB max
                'caption' => 'nullable|string|max:255',
                'type' => 'nullable|string|in:site_photo,design,blueprint,other',
            ]);

            $path = $file->store('galleries', 'public');

            $client->galleries()->create([
                'image_path' => $path,
                'caption' => $request->caption,
                'type' => $request->type ?? 'site_photo',
            ]);

            return back()->with('success', 'Image uploaded successfully: ' . $path);

        }
        catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Gallery Store Exception', ['message' => $e->getMessage()]);
            return back()->with('error', 'System Error: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ProjectGallery $gallery)
    {
        // Delete file from storage
        if (Storage::disk('public')->exists($gallery->image_path)) {
            Storage::disk('public')->delete($gallery->image_path);
        }

        // Delete record
        $gallery->delete();

        return back()->with('success', 'Image deleted successfully.');
    }
}