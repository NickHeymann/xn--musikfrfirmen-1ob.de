<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class MediaController extends Controller
{
    /**
     * Upload and optimize an image.
     */
    public function upload(Request $request)
    {
        $validated = $request->validate([
            'image' => 'required|image|max:10240', // Max 10MB
        ]);

        $image = $request->file('image');
        $filename = time() . '-' . uniqid() . '.webp'; // Always convert to WebP

        try {
            // Load and optimize image with Intervention/Image
            $img = Image::make($image)
                ->resize(2000, null, function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                })
                ->encode('webp', 85); // Convert to WebP with 85% quality

            // Store in public/storage/editor-images
            Storage::disk('public')->put("editor-images/{$filename}", $img);

            return response()->json([
                'url' => Storage::url("editor-images/{$filename}"),
                'filename' => $filename,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to upload image',
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
