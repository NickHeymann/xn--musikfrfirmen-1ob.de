<?php

namespace Database\Seeders;

use App\Models\Faq;
use Illuminate\Database\Seeder;

class CanvaFaqSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * Updates FAQs to match the Canva redesign content
     */
    public function run(): void
    {
        // Clear existing FAQs
        Faq::truncate();

        // New FAQ questions from Canva design
        $faqs = [
            [
                'question' => 'Sind Anfragen verbindlich?',
                'answer' => 'Nein, eure Anfragen sind völlig unverbindlich. Wir erstellen euch zunächst ein individuelles Angebot, das ihr in Ruhe prüfen könnt. Erst wenn ihr euch für eine Buchung entscheidet, wird der Vertrag verbindlich.',
                'has_link' => false,
            ],
            [
                'question' => 'Wie läuft eine Anfrage bei euch ab?',
                'answer' => "So einfach geht's:\n\n1. Ihr schickt uns eure Anfrage über unser Kontaktformular oder per WhatsApp.\n2. Wir melden uns innerhalb von 24 Stunden mit einem ersten Angebot.\n3. In einem Gespräch klären wir alle Details zu eurem Event.\n4. Ihr erhaltet ein maßgeschneidertes Angebot.\n5. Nach eurer Zusage kümmern wir uns um alles Weitere.\n\nEinfach, oder?",
                'has_link' => true,
            ],
            [
                'question' => 'Kann ich Sängerwünsche nennen?',
                'answer' => 'Absolut! Teilt uns einfach eure Wunschliste mit – ob bestimmte Songs, Genres oder sogar spezielle Künstler:innen. Unser vielseitiges Team kann ein breites Repertoire abdecken und wir stimmen das Programm individuell auf euch ab.',
                'has_link' => false,
            ],
            [
                'question' => 'Kann man auch deutschlandweit buchen?',
                'answer' => 'Ja, wir sind deutschlandweit für euch unterwegs! Ob Hamburg, München, Berlin oder Köln – wir kommen zu eurem Event. Für Veranstaltungen außerhalb Hamburgs fallen lediglich zusätzliche Fahrt- und ggf. Übernachtungskosten an.',
                'has_link' => false,
            ],
            [
                'question' => 'Was passiert, wenn die Sängerin/Sänger krank wird?',
                'answer' => 'Kein Grund zur Sorge! Dank unseres Netzwerks an professionellen Künstler:innen haben wir immer einen Plan B. Sollte jemand ausfallen, sorgen wir für gleichwertigen Ersatz – euer Event findet wie geplant statt.',
                'has_link' => false,
            ],
            [
                'question' => 'Muss ich mich noch um irgendetwas kümmern?',
                'answer' => 'Nein, entspannt euch! Wir übernehmen die komplette Organisation: Von der Künstlerauswahl über die technische Planung bis zum Auf- und Abbau. Ihr müsst euch nur um eine Sache kümmern: Euer Event genießen!',
                'has_link' => false,
            ],
            [
                'question' => 'Warum sollte ich mich also für eine Eventlösung mit musikfürfirmen buchen?',
                'answer' => "Weil ihr bei uns alles aus einer Hand bekommt:\n\n• Persönliche Beratung und ein fester Ansprechpartner\n• Handverlesene Profi-Musiker:innen, die wir persönlich kennen\n• Professionelle Technik im Wert von über 100.000 €\n• Kein Ausfallrisiko durch unser Künstler-Netzwerk\n• Rundum-sorglos-Service von Anfrage bis Abbau\n\nKurz: Wir machen euer Firmenevent musikalisch unvergesslich!",
                'has_link' => false,
            ],
        ];

        foreach ($faqs as $index => $faq) {
            Faq::create([
                'question' => $faq['question'],
                'answer' => $faq['answer'],
                'has_link' => $faq['has_link'],
                'display_order' => $index + 1,
                'status' => 'active',
            ]);
        }

        $this->command->info('Canva FAQ content seeded successfully!');
    }
}
