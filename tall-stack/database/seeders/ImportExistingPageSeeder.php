<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Page;

class ImportExistingPageSeeder extends Seeder
{
    /**
     * Import existing musikfuerfirmen.de homepage into visual editor format
     */
    public function run(): void
    {
        // Delete existing home page if it exists
        Page::where('slug', 'home')->delete();

        // Create home page with actual content blocks from the existing website
        Page::create([
            'slug' => 'home',
            'title' => 'Musik für Firmen - Home',
            'type' => 'content',
            'is_published' => true,
            'display_order' => 0,
            'content' => [
                'version' => '1.0',
                'type' => 'page',
                'blocks' => [
                    // Block 1: Hero Section
                    [
                        'id' => 'hero-1',
                        'type' => 'Hero',
                        'props' => [
                            'sliderContent' => ['Musik', 'Livebands', 'DJs', 'Technik'],
                            'backgroundVideo' => '/videos/hero-background.mp4',
                            'ctaText' => 'Unverbindliches Angebot anfragen',
                            'features' => [
                                'Musik für jedes Firmenevent',
                                'Rundum-sorglos-Paket',
                                'Angebot innerhalb von 24 Stunden'
                            ]
                        ]
                    ],

                    // Block 2: Service Cards
                    [
                        'id' => 'services-1',
                        'type' => 'ServiceCards',
                        'props' => [
                            'sectionId' => 'waswirbieten',
                            'paddingTop' => '187px'
                        ]
                    ],

                    // Block 3: Process Steps
                    [
                        'id' => 'process-1',
                        'type' => 'ProcessSteps',
                        'props' => [
                            'sectionId' => 'service',
                            'title' => 'Musik und Technik? Läuft.',
                            'subtitle' => 'Von uns geplant. Von euch gefeiert.',
                            'paddingTop' => '108px'
                        ]
                    ],

                    // Block 4: Team Section
                    [
                        'id' => 'team-1',
                        'type' => 'TeamSection',
                        'props' => [
                            'sectionId' => 'wir',
                            'paddingTop' => '178px'
                        ]
                    ],

                    // Block 5: FAQ Section
                    [
                        'id' => 'faq-1',
                        'type' => 'FAQ',
                        'props' => [
                            'sectionId' => 'faq',
                            'title' => 'FAQ',
                            'paddingTop' => '134px'
                        ]
                    ],

                    // Block 6: Logo Animation (Footer area)
                    [
                        'id' => 'logo-animation-1',
                        'type' => 'CTASection',
                        'props' => [
                            'paddingTop' => '190px',
                            'paddingBottom' => '163px'
                        ]
                    ]
                ]
            ]
        ]);

        $this->command->info('✓ Home page imported with 6 blocks');
    }
}
