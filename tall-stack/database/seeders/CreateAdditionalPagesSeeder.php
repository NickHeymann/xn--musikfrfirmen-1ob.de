<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Page;

class CreateAdditionalPagesSeeder extends Seeder
{
    public function run(): void
    {
        // Create Impressum page
        Page::updateOrCreate(
            ['slug' => 'impressum'],
            [
                'title' => 'Impressum',
                'type' => 'content',
                'is_published' => true,
                'display_order' => 10,
                'content' => [
                    'version' => '1.0',
                    'type' => 'page',
                    'blocks' => [
                        [
                            'id' => 'impressum-header',
                            'type' => 'TextBlock',
                            'props' => [
                                'heading' => 'Impressum',
                                'content' => '<h1>Impressum</h1><p>Angaben gemäß § 5 TMG</p>',
                            ]
                        ],
                    ]
                ]
            ]
        );

        // Create Datenschutz page
        Page::updateOrCreate(
            ['slug' => 'datenschutz'],
            [
                'title' => 'Datenschutz',
                'type' => 'content',
                'is_published' => true,
                'display_order' => 11,
                'content' => [
                    'version' => '1.0',
                    'type' => 'page',
                    'blocks' => [
                        [
                            'id' => 'datenschutz-header',
                            'type' => 'TextBlock',
                            'props' => [
                                'heading' => 'Datenschutz',
                                'content' => '<h1>Datenschutzerklärung</h1><p>Ihre Daten sind uns wichtig.</p>',
                            ]
                        ],
                    ]
                ]
            ]
        );

        // Create Ueber-Uns page
        Page::updateOrCreate(
            ['slug' => 'ueber-uns'],
            [
                'title' => 'Über Uns',
                'type' => 'content',
                'is_published' => true,
                'display_order' => 2,
                'content' => [
                    'version' => '1.0',
                    'type' => 'page',
                    'blocks' => [
                        [
                            'id' => 'ueber-uns-header',
                            'type' => 'TextBlock',
                            'props' => [
                                'heading' => 'Über Uns',
                                'content' => '<h1>Über Uns</h1><p>Wir sind musikfürfirmen.de</p>',
                            ]
                        ],
                    ]
                ]
            ]
        );

        $this->command->info('✓ Additional pages created successfully');
    }
}
