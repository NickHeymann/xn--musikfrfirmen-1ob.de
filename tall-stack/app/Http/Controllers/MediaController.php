<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class MediaController extends Controller
{
    /**
     * Upload and process media (images/videos).
     * POST /api/pages/media
     */
    public function upload(Request $request)
    {
        $validated = $request->validate([
            'file' => 'required|file|mimes:jpeg,jpg,png,gif,webp,mp4,mov,avi|max:51200', // Max 50MB
            'blockId' => 'nullable|string',
            'path' => 'nullable|string',
        ]);

        $file = $request->file('file');
        $mimeType = $file->getMimeType();
        $baseFilename = time() . '-' . uniqid();

        try {
            // Handle images
            if (str_starts_with($mimeType, 'image/')) {
                return $this->processImage($file, $baseFilename);
            }

            // Handle videos
            if (str_starts_with($mimeType, 'video/')) {
                return $this->processVideo($file, $baseFilename);
            }

            return response()->json([
                'error' => 'Unsupported file type',
            ], 400);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to upload media',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Process and optimize image with multiple sizes.
     */
    private function processImage($file, $baseFilename)
    {
        $img = Image::make($file);

        // Store original
        $originalFilename = "{$baseFilename}-original.jpg";
        $originalImg = clone $img;
        $originalImg->encode('jpg', 95);
        Storage::disk('public')->put("images/{$originalFilename}", $originalImg);

        // Generate WebP versions (1x and 2x)
        $webp1xFilename = "{$baseFilename}-1x.webp";
        $webp1x = clone $img;
        $webp1x->resize(1920, null, function ($constraint) {
            $constraint->aspectRatio();
            $constraint->upsize();
        })->encode('webp', 85);
        Storage::disk('public')->put("images/{$webp1xFilename}", $webp1x);

        $webp2xFilename = "{$baseFilename}-2x.webp";
        $webp2x = clone $img;
        $webp2x->resize(3840, null, function ($constraint) {
            $constraint->aspectRatio();
            $constraint->upsize();
        })->encode('webp', 85);
        Storage::disk('public')->put("images/{$webp2xFilename}", $webp2x);

        // Generate JPEG fallback
        $jpegFilename = "{$baseFilename}.jpg";
        $jpeg = clone $img;
        $jpeg->resize(1920, null, function ($constraint) {
            $constraint->aspectRatio();
            $constraint->upsize();
        })->encode('jpg', 85);
        Storage::disk('public')->put("images/{$jpegFilename}", $jpeg);

        // Generate thumbnail
        $thumbnailFilename = "{$baseFilename}-thumb.jpg";
        $thumbnail = clone $img;
        $thumbnail->fit(400, 400)->encode('jpg', 80);
        Storage::disk('public')->put("images/{$thumbnailFilename}", $thumbnail);

        return response()->json([
            'url' => Storage::url("images/{$jpegFilename}"),
            'thumbnail' => Storage::url("images/{$thumbnailFilename}"),
            'sizes' => [
                '1x' => Storage::url("images/{$webp1xFilename}"),
                '2x' => Storage::url("images/{$webp2xFilename}"),
            ],
            'original' => Storage::url("images/{$originalFilename}"),
        ]);
    }

    /**
     * Process video upload.
     */
    private function processVideo($file, $baseFilename)
    {
        $videoFilename = "{$baseFilename}.mp4";
        $path = $file->storeAs('videos', $videoFilename, 'public');

        return response()->json([
            'url' => Storage::url($path),
            'type' => 'video',
        ]);
    }

    /**
     * Apply adjustments to existing image.
     * POST /api/pages/media/adjust
     */
    public function adjust(Request $request)
    {
        $validated = $request->validate([
            'url' => 'required|string',
            'adjustments' => 'required|array',
            'adjustments.exposure' => 'nullable|integer|min:-100|max:100',
            'adjustments.contrast' => 'nullable|integer|min:-100|max:100',
            'adjustments.highlights' => 'nullable|integer|min:-100|max:100',
            'adjustments.shadows' => 'nullable|integer|min:-100|max:100',
            'adjustments.blacks' => 'nullable|integer|min:-100|max:100',
            'adjustments.opacity' => 'nullable|integer|min:0|max:100',
        ]);

        try {
            // Extract original filename from URL
            $originalPath = str_replace(Storage::url(''), '', $validated['url']);

            if (!Storage::disk('public')->exists($originalPath)) {
                return response()->json([
                    'error' => 'Original image not found',
                ], 404);
            }

            // Load original image
            $img = Image::make(Storage::disk('public')->get($originalPath));

            // Apply adjustments
            $adjustments = $validated['adjustments'];

            if (isset($adjustments['exposure'])) {
                $img->brightness($adjustments['exposure']);
            }

            if (isset($adjustments['contrast'])) {
                $img->contrast($adjustments['contrast']);
            }

            // Note: Intervention Image doesn't have highlights/shadows/blacks directly
            // These would need custom implementation or different library
            // For MVP, we'll handle exposure and contrast

            if (isset($adjustments['opacity']) && $adjustments['opacity'] < 100) {
                $img->opacity($adjustments['opacity']);
            }

            // Generate new processed images
            $baseFilename = time() . '-' . uniqid();
            return $this->saveProcessedImage($img, $baseFilename);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to adjust image',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Save processed image with multiple formats.
     */
    private function saveProcessedImage($img, $baseFilename)
    {
        // Generate WebP 1x
        $webp1xFilename = "{$baseFilename}-1x.webp";
        $webp1x = clone $img;
        $webp1x->resize(1920, null, function ($constraint) {
            $constraint->aspectRatio();
            $constraint->upsize();
        })->encode('webp', 85);
        Storage::disk('public')->put("images/{$webp1xFilename}", $webp1x);

        // Generate WebP 2x
        $webp2xFilename = "{$baseFilename}-2x.webp";
        $webp2x = clone $img;
        $webp2x->resize(3840, null, function ($constraint) {
            $constraint->aspectRatio();
            $constraint->upsize();
        })->encode('webp', 85);
        Storage::disk('public')->put("images/{$webp2xFilename}", $webp2x);

        // Generate JPEG fallback
        $jpegFilename = "{$baseFilename}.jpg";
        $jpeg = clone $img;
        $jpeg->resize(1920, null, function ($constraint) {
            $constraint->aspectRatio();
            $constraint->upsize();
        })->encode('jpg', 85);
        Storage::disk('public')->put("images/{$jpegFilename}", $jpeg);

        // Generate thumbnail
        $thumbnailFilename = "{$baseFilename}-thumb.jpg";
        $thumbnail = clone $img;
        $thumbnail->fit(400, 400)->encode('jpg', 80);
        Storage::disk('public')->put("images/{$thumbnailFilename}", $thumbnail);

        return response()->json([
            'url' => Storage::url("images/{$jpegFilename}"),
            'thumbnail' => Storage::url("images/{$thumbnailFilename}"),
            'sizes' => [
                '1x' => Storage::url("images/{$webp1xFilename}"),
                '2x' => Storage::url("images/{$webp2xFilename}"),
            ],
        ]);
    }
}
