<?php

namespace Database\Seeders;

use App\Models\Testimonial;
use Illuminate\Database\Seeder;

class TestimonialSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $testimonials = [
            [
                'quote' => 'Wir haben die Band für die Feier zum 25. Jubiläum unseres Unternehmens engagiert. Die Band hat unsere Erwartungen mehr als übertroffen. Vielen Dank. Ich kann die Band von ganzem Herzen jedem nur weiterempfehlen.',
                'author_name' => 'Peter Weber',
                'author_position' => 'CEO',
                'author_company' => 'Let Me Ship GmbH',
                'is_featured' => true,
                'is_active' => true,
                'display_order' => 1,
            ],
            [
                'quote' => 'Die Band hat bei unserem Firmenevent gespielt und für eine fantastische Atmosphäre gesorgt. Die Songauswahl war perfekt abgestimmt und die Musiker total sympathisch. Klare Empfehlung für alle, die echte Livemusik schätzen!',
                'author_name' => 'Anonymous',
                'author_position' => null,
                'author_company' => null,
                'is_featured' => true,
                'is_active' => true,
                'display_order' => 2,
            ],
            [
                'quote' => 'Diese Band ist ein absolutes Highlight. Mit ihrer fantastischen Stimme hat die Lead Sängerin sofort alle in ihren Bann gezogen. Mit einem sehr umfangreichen Repertoire bieten sie den gesamten Abend ein abwechslungsreiches Programm das jeden Wunsch erfüllt. Ich würde auch 6 Sterne vergeben .....ein absoluter Tipp für größere Veranstaltungen! Danke für den unvergesslichen Abend',
                'author_name' => 'Anonymous',
                'author_position' => null,
                'author_company' => null,
                'is_featured' => true,
                'is_active' => true,
                'display_order' => 3,
            ],
            [
                'quote' => 'Wir haben die Band bei einem Firmenevent mit Open-air Bühne erlebt und sind absolut begeistert. Die Band hat das gesamte Publikum in ihren Bann gezogen und mit der Auswahl der Lieder die Stimmung immer mehr gesteigert, so dass alle getanzt haben und Zugaben gefordert wurden.',
                'author_name' => 'Anonymous',
                'author_position' => null,
                'author_company' => null,
                'is_featured' => true,
                'is_active' => true,
                'display_order' => 4,
            ],
        ];

        foreach ($testimonials as $testimonial) {
            Testimonial::create($testimonial);
        }
    }
}
