<?php

namespace Database\Seeders;

use App\Models\Page;
use Illuminate\Database\Seeder;

class ConvertHomepageSeeder extends Seeder
{
    /**
     * Seed the database with initial homepage data.
     */
    public function run(): void
    {
        // Create homepage with existing content structure
        Page::create([
            'slug' => 'home',
            'title' => 'Musik für Firmen - Home',
            'meta_title' => 'Musik und Technik für dein Event',
            'meta_description' => 'Professionelle Musikdienstleistungen für Firmenevents',
            'content' => [
                'version' => '1.0',
                'type' => 'page',
                'blocks' => [
                    [
                        'id' => 'hero-1',
                        'type' => 'Hero',
                        'props' => [
                            'sliderContent' => ['Musik', 'Livebands', 'Djs', 'Technik'],
                        ],
                    ],
                    [
                        'id' => 'services-1',
                        'type' => 'ServiceCards',
                        'props' => [
                            'services' => [
                                [
                                    'title' => 'Live Bands',
                                    'description' => 'Professionelle Live-Musik für jeden Anlass',
                                    'icon' => '🎵',
                                ],
                                [
                                    'title' => 'DJ Services',
                                    'description' => 'Moderne DJ-Technik und große Musikauswahl',
                                    'icon' => '🎧',
                                ],
                                [
                                    'title' => 'Tontechnik',
                                    'description' => 'Professionelle Audio-Ausstattung für Events',
                                    'icon' => '🔊',
                                ],
                            ],
                        ],
                    ],
                    [
                        'id' => 'process-1',
                        'type' => 'ProcessSteps',
                        'props' => [
                            'heading' => 'So einfach geht\'s',
                            'steps' => [
                                [
                                    'number' => 1,
                                    'title' => 'Anfrage senden',
                                    'description' => 'Kontaktieren Sie uns mit Ihren Event-Details',
                                ],
                                [
                                    'number' => 2,
                                    'title' => 'Beratung',
                                    'description' => 'Wir besprechen Ihre Wünsche und erstellen ein Angebot',
                                ],
                                [
                                    'number' => 3,
                                    'title' => 'Event durchführen',
                                    'description' => 'Wir sorgen für unvergessliche Musik an Ihrem Event',
                                ],
                            ],
                        ],
                    ],
                    [
                        'id' => 'footer-1',
                        'type' => 'Footer',
                        'props' => [
                            'companyName' => 'Musik für Firmen',
                            'email' => 'info@musikfuerfirmen.de',
                            'phone' => '+49 123 456789',
                        ],
                    ],
                ],
            ],
        ]);

        // Create additional example pages
        Page::create([
            'slug' => 'services',
            'title' => 'Unsere Services',
            'meta_title' => 'Unsere Dienstleistungen - Musik für Firmen',
            'content' => [
                'version' => '1.0',
                'type' => 'page',
                'blocks' => [],
            ],
        ]);

        Page::create([
            'slug' => 'about',
            'title' => 'Über uns',
            'meta_title' => 'Über uns - Musik für Firmen',
            'content' => [
                'version' => '1.0',
                'type' => 'page',
                'blocks' => [],
            ],
        ]);
    }
}
