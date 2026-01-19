<?php

namespace Database\Seeders;

use App\Models\Service;
use App\Models\TeamMember;
use App\Models\Faq;
use Illuminate\Database\Seeder;

/**
 * NextJsContentSeeder
 *
 * Imports content from Next.js TypeScript data files into TALL stack database.
 * Data source: ../../../src/data/*.ts
 */
class NextJsContentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear existing data
        Service::truncate();
        TeamMember::truncate();
        Faq::truncate();

        $this->seedServices();
        $this->seedTeamMembers();
        $this->seedFaqs();

        $this->command->info('✅ Next.js content imported successfully!');
    }

    /**
     * Seed services from src/data/services.ts
     */
    private function seedServices(): void
    {
        $services = [
            [
                'title' => '60 Sekunden',
                'text' => 'Schickt uns eure Anfrage innerhalb von ',
                'highlight' => '60 Sekunden',
                'description' => ' über unser Formular. Schnell, einfach und unkompliziert.',
                'display_order' => 1,
                'status' => 'active',
            ],
            [
                'title' => '24 Stunden',
                'text' => 'Erhaltet ein kostenloses Angebot innerhalb von ',
                'highlight' => '24 Stunden',
                'description' => '. Durch das von euch ausgefüllte Formular liefern wir euch ein individuelles Angebot.',
                'display_order' => 2,
                'status' => 'active',
            ],
            [
                'title' => 'Rundum-Service',
                'text' => 'Genießt ',
                'highlight' => 'professionelle Betreuung',
                'description' => ' bis zum großen Tag! Wir sind 24/7 erreichbar. Über WhatsApp, Telefon oder E-Mail.',
                'display_order' => 3,
                'status' => 'active',
            ],
        ];

        foreach ($services as $service) {
            Service::create($service);
        }

        $this->command->info('  → Services imported (3)');
    }

    /**
     * Seed team members from src/data/team.ts
     */
    private function seedTeamMembers(): void
    {
        $teamMembers = [
            [
                'name' => 'Jonas Glamann',
                'role' => 'Direkter Kontakt vor Ort',
                'role_second_line' => 'Koordination von Band + Technik, Gitarrist',
                'image' => '/images/team/jonas.png',
                'bio_title' => 'Der Kreative',
                'bio_text' => 'Hi, ich bin Jonas. Ich bin euer direkter Kontakt vor Ort. Mit 7 Jahren habe ich angefangen Gitarre zu spielen und stehe seitdem auf der Bühne. Ich bin selbst Teil der Band und koordiniere diese, sowie alles rund um Technik. Ich halte die Abläufe zusammen.

Vor Musikfürfirmen.de habe ich selbst in vielen Coverbands gespielt. Parallel dazu habe ich als Techniker Bands wie Revolverheld und Adel Tawil auf Tour begleitet. Ich bin dadurch mit beiden Seiten von Events vertraut und weiß genau, wie ich mit allen Beteiligten kommunizieren muss.',
                'image_class' => 'img1',
                'position' => 'left',
                'display_order' => 1,
                'status' => 'active',
            ],
            [
                'name' => 'Nick Heymann',
                'role' => 'Marketingspezialist',
                'role_second_line' => 'Ansprechpartner und Videograf',
                'image' => '/images/team/nick.png',
                'bio_title' => 'Der Macher',
                'bio_text' => 'Hi, ich bin Nick. Ich bin euer allgemeiner Ansprechpartner und kümmere ich mich um das Marketing und den visuellen Auftritt bei Musikfürfirmen.de.

Nach meinem Musikstudium habe ich mich als Videograf und Marketingberater selbständig gemacht. Meine Videos von verschiedenen Events sammelten über 100 Millionen Aufrufe auf Social Media.

Auf Wunsch begleite ich euer Event videographisch und liefere Material für interne Kommunikation oder Socials und eure Website.',
                'image_class' => 'img2',
                'position' => 'right',
                'display_order' => 2,
                'status' => 'active',
            ],
        ];

        foreach ($teamMembers as $member) {
            TeamMember::create($member);
        }

        $this->command->info('  → Team members imported (2)');
    }

    /**
     * Seed FAQs from src/data/faq.ts
     */
    private function seedFaqs(): void
    {
        $faqs = [
            [
                'question' => 'Sind Anfragen verbindlich?',
                'answer' => 'Nein, Anfragen sind komplett unverbindlich und werden innerhalb von 24 Stunden beantwortet. Gerne bieten wir dir auch ein kostenloses Erstgespräch an.',
                'has_link' => false,
                'display_order' => 1,
                'status' => 'active',
            ],
            [
                'question' => 'Wie läuft eine Anfrage bei euch ab?',
                'answer' => 'In nur drei Schritten:

1) Klick auf "Unverbindliches Angebot anfragen"
2) Fülle das Formular mit den wichtigsten Infos zu deinem Event aus
3) Drücke auf Absenden.

Innerhalb von 24 Stunden hast du dein individuelles Angebot im Postfach.',
                'has_link' => true,
                'display_order' => 2,
                'status' => 'active',
            ],
            [
                'question' => 'Kann ich Songwünsche nennen?',
                'answer' => 'Auf jeden Fall! Unsere Bands haben zwar ein festes Repertoire, freuen sich aber über besondere Songwünsche. Erwähne sie einfach im Erstgespräch, so bleibt genug Zeit für die Vorbereitung.',
                'has_link' => false,
                'display_order' => 3,
                'status' => 'active',
            ],
            [
                'question' => 'Kann man euch deutschlandweit buchen?',
                'answer' => 'Absolut! Egal wo in Deutschland dein Event stattfindet, du kannst auf uns zählen. Um Anfahrt, Logistik und Unterkunft kümmern wir uns.',
                'has_link' => false,
                'display_order' => 4,
                'status' => 'active',
            ],
            [
                'question' => 'Was passiert, wenn die Sängerin/Sänger krank wird?',
                'answer' => 'Keine Sorge, dafür sind wir vorbereitet! Für alle unsere Künstler:innen haben wir erfahrene Ersatzleute parat, die kurzfristig einspringen können. Sollte es dazu kommen, melden wir uns natürlich sofort bei dir.',
                'has_link' => false,
                'display_order' => 5,
                'status' => 'active',
            ],
            [
                'question' => 'Muss ich mich noch um irgendetwas kümmern?',
                'answer' => 'Nein! Wir nehmen dir alles ab, was mit Musik zu tun hat: von der Auswahl der passenden Künstler:innen über Equipment und Technik bis hin zu Anfahrt und Übernachtung. Du kannst dich entspannt auf dein Event freuen.',
                'has_link' => false,
                'display_order' => 6,
                'status' => 'active',
            ],
            [
                'question' => 'Warum sollte ich nicht alles über eine Eventagentur buchen?',
                'answer' => 'Gute Frage! Bei den meisten Eventagenturen läuft Musik eher nebenbei. Ob die Band gut ist, wird dann zur Glückssache. Wir sehen das anders: Musik prägt die Stimmung und bleibt in Erinnerung. Deshalb geben wir ihr die Aufmerksamkeit, die sie verdient.',
                'has_link' => false,
                'display_order' => 7,
                'status' => 'active',
            ],
        ];

        foreach ($faqs as $faq) {
            Faq::create($faq);
        }

        $this->command->info('  → FAQs imported (7)');
    }
}
