<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Symfony\Component\DomCrawler\Crawler;

class ServicePagesSeeder extends Seeder
{
    /**
     * Service-related keywords for identifying service pages
     */
    private array $serviceKeywords = [
        'coaching',
        'beratung',
        'begleitung',
        'retreat',
        'workshop',
        'training',
        'einzelbegleitung',
        'paarbegleitung',
        'gruppenbegleitung',
    ];

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('Starting service pages import...');

        // Get all HTML files from parent directory
        $htmlFiles = glob(base_path('../*.html'));

        if (empty($htmlFiles)) {
            $this->command->error('No HTML files found');
            return;
        }

        $this->command->info('Found ' . count($htmlFiles) . ' HTML files, filtering for service pages...');

        $imported = 0;
        $skipped = 0;

        foreach ($htmlFiles as $file) {
            try {
                $filename = basename($file);

                // Filter: Only process files matching service keywords
                if (!$this->isServicePage($filename)) {
                    continue;
                }

                $this->command->info("Processing: {$filename}");

                // Load HTML content
                $html = File::get($file);
                $crawler = new Crawler($html);

                // Extract metadata
                $title = $this->extractTitle($crawler);
                $slug = $this->extractSlug($filename);
                $description = $this->extractDescription($crawler);
                $excerpt = $this->extractExcerpt($crawler);
                $thumbnailImage = $this->extractThumbnail($crawler);
                $metaDescription = $this->extractMetaDescription($crawler);
                $features = $this->extractFeatures($html);
                $price = $this->extractPrice($html);
                $duration = $this->extractDuration($html);

                if (empty($title)) {
                    $this->command->warn("Skipping {$filename} - no title found");
                    $skipped++;
                    continue;
                }

                // Insert service
                DB::table('services')->insert([
                    'title' => $title,
                    'slug' => $slug,
                    'excerpt' => $excerpt,
                    'description' => $description,
                    'price' => $price,
                    'duration' => $duration,
                    'features' => json_encode($features, JSON_UNESCAPED_UNICODE),
                    'thumbnail_image' => $thumbnailImage,
                    'meta_description' => $metaDescription,
                    'meta_keywords' => json_encode($this->extractKeywords($metaDescription), JSON_UNESCAPED_UNICODE),
                    'display_order' => 0,
                    'status' => 'active',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                $imported++;
                $this->command->info("✓ Imported: {$title}");

            } catch (\Exception $e) {
                $this->command->error('Failed to import ' . basename($file) . ': ' . $e->getMessage());
                $skipped++;
            }
        }

        $this->command->info("\n" . str_repeat('=', 60));
        $this->command->info('Service Pages Import Complete');
        $this->command->info(str_repeat('=', 60));
        $this->command->info("✓ Imported: {$imported} services");
        $this->command->info("✗ Skipped: {$skipped} files");
    }

    /**
     * Check if filename matches service page pattern
     */
    private function isServicePage(string $filename): bool
    {
        $filenameLower = strtolower($filename);

        foreach ($this->serviceKeywords as $keyword) {
            if (str_contains($filenameLower, $keyword)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Extract title from HTML
     */
    private function extractTitle(Crawler $crawler): ?string
    {
        // Try og:title first
        try {
            $ogTitle = $crawler->filter('meta[property="og:title"]')->attr('content');
            if ($ogTitle) {
                // Remove " | Kathrin Stahl" or similar suffixes
                return preg_replace('/\s*[|–-]\s*KATHRIN STAHL\s*$/i', '', $ogTitle);
            }
        } catch (\Exception $e) {
            // Fall through to next method
        }

        // Try title tag
        try {
            $title = $crawler->filter('title')->text();
            if ($title) {
                return preg_replace('/\s*[|–-]\s*KATHRIN STAHL\s*$/i', '', $title);
            }
        } catch (\Exception $e) {
            // Fall through
        }

        // Try h1 in main content
        try {
            return $crawler->filter('main h1, article h1, .content h1')->first()->text();
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * Extract slug from filename
     */
    private function extractSlug(string $filename): string
    {
        // Remove .html extension
        $slug = str_replace('.html', '', $filename);

        // Clean and slugify
        return Str::slug($slug);
    }

    /**
     * Extract meta description
     */
    private function extractMetaDescription(Crawler $crawler): ?string
    {
        try {
            return $crawler->filter('meta[name="description"]')->attr('content');
        } catch (\Exception $e) {
            try {
                return $crawler->filter('meta[property="og:description"]')->attr('content');
            } catch (\Exception $e) {
                return null;
            }
        }
    }

    /**
     * Extract excerpt (short description)
     */
    private function extractExcerpt(Crawler $crawler): ?string
    {
        $metaDesc = $this->extractMetaDescription($crawler);
        
        if ($metaDesc && strlen($metaDesc) <= 300) {
            return $metaDesc;
        }

        // Try first paragraph
        try {
            $firstPara = $crawler->filter('main p, article p, .content p')->first()->text();
            return Str::limit($firstPara, 300);
        } catch (\Exception $e) {
            return $metaDesc ? Str::limit($metaDesc, 300) : null;
        }
    }

    /**
     * Extract main description/content
     */
    private function extractDescription(Crawler $crawler): string
    {
        // Try main content areas
        try {
            return $crawler->filter('main, article, .content, .entry-content')->first()->html();
        } catch (\Exception $e) {
            // Fallback to body
            try {
                return $crawler->filter('body')->html();
            } catch (\Exception $e) {
                return '';
            }
        }
    }

    /**
     * Extract thumbnail image
     */
    private function extractThumbnail(Crawler $crawler): ?string
    {
        // Try og:image
        try {
            return $crawler->filter('meta[property="og:image"]')->attr('content');
        } catch (\Exception $e) {
            // Try featured image
            try {
                return $crawler->filter('.featured-image img, .hero-image img')->first()->attr('src');
            } catch (\Exception $e) {
                return null;
            }
        }
    }

    /**
     * Extract features from structured data or content
     */
    private function extractFeatures(string $html): array
    {
        $features = [];

        // Try to extract from bullet lists (common pattern in service pages)
        if (preg_match_all('/<li[^>]*>([^<]+)<\/li>/i', $html, $matches)) {
            // Take up to 10 list items as potential features
            $items = array_slice($matches[1], 0, 10);
            
            foreach ($items as $item) {
                $cleaned = strip_tags(html_entity_decode($item));
                $cleaned = trim($cleaned);
                
                // Only include if it looks like a feature (not too long, not empty)
                if (strlen($cleaned) > 3 && strlen($cleaned) < 150) {
                    $features[] = $cleaned;
                }
            }
        }

        // Remove duplicates
        return array_values(array_unique($features));
    }

    /**
     * Extract price from content
     */
    private function extractPrice(string $html): ?float
    {
        // Look for price patterns like "€1200", "1200€", "1.200 EUR"
        if (preg_match('/€\s*(\d+(?:[.,]\d+)?)|(\d+(?:[.,]\d+)?)\s*€/i', $html, $matches)) {
            $priceStr = $matches[1] ?? $matches[2];
            $priceStr = str_replace(['.', ','], ['', '.'], $priceStr);
            return (float) $priceStr;
        }

        return null;
    }

    /**
     * Extract duration from content
     */
    private function extractDuration(string $html): ?string
    {
        // Look for duration patterns like "60 Minuten", "3 Monate", "90 min"
        if (preg_match('/(\d+)\s*(Minuten|Stunden|Tage|Wochen|Monate|min|h|d)/i', $html, $matches)) {
            return $matches[0];
        }

        return null;
    }

    /**
     * Extract keywords from text
     */
    private function extractKeywords(string $text): array
    {
        if (empty($text)) {
            return [];
        }

        // Simple keyword extraction: common coaching-related terms
        $commonKeywords = [
            'coaching',
            'beratung',
            'begleitung',
            'hochsensibilität',
            'sinnkrise',
            'lebensumbruch',
            'beziehung',
            'retreat',
            'achtsamkeit',
            'selbstfindung',
        ];

        $keywords = [];
        $textLower = strtolower($text);

        foreach ($commonKeywords as $keyword) {
            if (str_contains($textLower, $keyword)) {
                $keywords[] = $keyword;
            }
        }

        return array_values(array_unique($keywords));
    }
}
