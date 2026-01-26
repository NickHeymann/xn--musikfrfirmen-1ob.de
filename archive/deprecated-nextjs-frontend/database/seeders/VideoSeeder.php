<?php

namespace Database\Seeders;

use App\Models\Video;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class VideoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * 
     * Migrates videos from data/videos.json to the videos table.
     * 
     * Field Mapping:
     * - id → youtube_id
     * - title → title
     * - description → description
     * - published → published_on_youtube
     * - category → category
     * 
     * Expected JSON structure:
     * [
     *   {
     *     "id": "k8ez_RptaA4",
     *     "title": "Video Title",
     *     "description": "Video description...",
     *     "published": "2025-11-28",
     *     "category": "selbstfindung"
     *   },
     *   ...
     * ]
     */
    public function run(): void
    {
        $jsonPath = base_path('data/videos.json');

        // Check if JSON file exists
        if (!File::exists($jsonPath)) {
            $this->command->error("❌ videos.json not found at: {$jsonPath}");
            $this->command->info("Expected path: " . $jsonPath);
            return;
        }

        // Read and parse JSON
        $jsonContent = File::get($jsonPath);
        $videosData = json_decode($jsonContent, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            $this->command->error('❌ Invalid JSON in videos.json: ' . json_last_error_msg());
            return;
        }

        $this->command->info("📋 Found " . count($videosData) . " videos in JSON");

        $imported = 0;
        $skipped = 0;
        $errors = 0;

        foreach ($videosData as $index => $videoData) {
            try {
                // Validate required fields
                if (!isset($videoData['id'])) {
                    $this->command->warn("⚠️  Video #{$index}: Missing youtube_id, skipping");
                    $errors++;
                    continue;
                }

                // Skip if youtube_id already exists (avoid duplicates)
                if (Video::where('youtube_id', $videoData['id'])->exists()) {
                    $skipped++;
                    continue;
                }

                // Create video record
                Video::create([
                    'youtube_id' => $videoData['id'],
                    'title' => $videoData['title'] ?? 'Untitled Video',
                    'description' => $videoData['description'] ?? null,
                    'category' => $videoData['category'] ?? 'allgemein',
                    'published_on_youtube' => isset($videoData['published']) 
                        ? $videoData['published'] 
                        : now(),
                    'status' => 'active',
                    
                    // Metadata fields (will be populated later via YouTube API if needed)
                    'thumbnail_url' => null,
                    'duration_seconds' => null,
                    'view_count' => 0,
                    'tags' => null,
                ]);

                $imported++;

                // Progress indicator every 25 videos
                if ($imported % 25 === 0) {
                    $this->command->info("  ... {$imported} videos imported");
                }

            } catch (\Exception $e) {
                $this->command->error("❌ Error importing video #{$index}: " . $e->getMessage());
                $errors++;
            }
        }

        // Final summary
        $this->command->newLine();
        $this->command->info("✅ Migration Complete!");
        $this->command->info("   Imported: {$imported} videos");
        
        if ($skipped > 0) {
            $this->command->info("   ⏭️  Skipped (duplicates): {$skipped} videos");
        }
        
        if ($errors > 0) {
            $this->command->warn("   ⚠️  Errors: {$errors} videos");
        }

        $this->command->newLine();
    }
}
