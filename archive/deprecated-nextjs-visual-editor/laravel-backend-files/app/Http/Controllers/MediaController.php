<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;

class MediaController extends Controller
{
    /**
     * Upload and optimize an image.
     * Legacy endpoint - kept for backward compatibility.
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

    /**
     * Upload file to temporary storage.
     * Supports images (jpg, png, webp) and videos (mp4, webm).
     */
    public function uploadTemp(Request $request)
    {
        $validated = $request->validate([
            'file' => 'required|file|mimes:jpg,jpeg,png,webp,mp4,webm|max:10240', // Max 10MB
        ]);

        $file = $request->file('file');
        $tempId = Str::random(32);
        $extension = $file->getClientOriginalExtension();
        $filename = $tempId . '.' . $extension;

        try {
            // Store in temp directory
            $file->storeAs('temp', $filename);

            return response()->json([
                'tempId' => $tempId,
                'tempUrl' => '/storage/temp/' . $filename,
                'filename' => $file->getClientOriginalName(),
                'size' => $file->getSize(),
                'type' => $file->getMimeType(),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to upload temporary file',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Commit temporary file to permanent storage.
     * Moves file from temp to uploads/{path}/.
     */
    public function commitTemp(Request $request)
    {
        $validated = $request->validate([
            'tempId' => 'required|string',
            'path' => 'nullable|string|in:hero,services,team,general',
        ]);

        $tempId = $validated['tempId'];
        $subfolder = $validated['path'] ?? 'general';

        try {
            // Find temp file
            $tempFiles = Storage::files('temp');
            $tempFile = collect($tempFiles)->first(function ($file) use ($tempId) {
                return str_contains($file, $tempId);
            });

            if (!$tempFile) {
                return response()->json([
                    'error' => 'Temporary file not found',
                ], 404);
            }

            // Move to permanent storage
            $filename = basename($tempFile);
            $destination = 'public/uploads/' . $subfolder . '/' . $filename;

            Storage::move($tempFile, $destination);

            return response()->json([
                'url' => '/storage/uploads/' . $subfolder . '/' . $filename,
                'filename' => $filename,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to commit temporary file',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Delete temporary file.
     */
    public function deleteTemp($tempId)
    {
        try {
            $tempFiles = Storage::files('temp');
            $tempFile = collect($tempFiles)->first(function ($file) use ($tempId) {
                return str_contains($file, $tempId);
            });

            if (!$tempFile) {
                return response()->json([
                    'error' => 'Temporary file not found',
                ], 404);
            }

            Storage::delete($tempFile);

            return response()->json([
                'success' => true,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to delete temporary file',
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
