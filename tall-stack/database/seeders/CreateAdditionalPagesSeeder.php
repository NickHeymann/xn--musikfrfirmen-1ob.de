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
                'type' => 'legal',
                'is_published' => true,
                'display_order' => 10,
                'content' => [
                    'version' => '1.0',
                    'type' => 'page',
                    'blocks' => [
                        [
                            'id' => 'impressum-content',
                            'type' => 'TextBlock',
                            'props' => [
                                'content' => '
                                    <section class="space-y-10">
                                        <div>
                                            <h2 class="text-xl font-semibold text-[#1a1a1a] mb-4">Angaben gemäß § 5 TMG</h2>
                                            <div class="bg-[#f9faf9] rounded-xl p-6 leading-relaxed">
                                                <p class="font-medium text-[#1a1a1a]">Jonas Glamann</p>
                                                <p>musikfürfirmen.de</p>
                                                <p class="mt-2">Hellbrookstraße 42a</p>
                                                <p>22305 Hamburg</p>
                                            </div>
                                        </div>
                                        <div>
                                            <h2 class="text-xl font-semibold text-[#1a1a1a] mb-4">Kontakt</h2>
                                            <div class="bg-[#f9faf9] rounded-xl p-6 leading-relaxed">
                                                <p><span class="text-[#666]">Telefon:</span> <a href="tel:+491741699553" class="text-[#0D7A5F] hover:underline">+49 174 1699553</a></p>
                                                <p class="mt-2"><span class="text-[#666]">E-Mail:</span> <a href="mailto:kontakt@musikfuerfirmen.de" class="text-[#0D7A5F] hover:underline">kontakt@musikfuerfirmen.de</a></p>
                                            </div>
                                        </div>
                                        <div>
                                            <h2 class="text-xl font-semibold text-[#1a1a1a] mb-4">Umsatzsteuer-ID</h2>
                                            <p class="leading-relaxed">Umsatzsteuer-Identifikationsnummer gemäß § 27 a Umsatzsteuergesetz:</p>
                                            <p class="mt-2 font-medium">DE406490626</p>
                                        </div>
                                        <div>
                                            <h2 class="text-xl font-semibold text-[#1a1a1a] mb-4">Verantwortlich für den Inhalt nach § 55 Abs. 2 RStV</h2>
                                            <p class="leading-relaxed">Jonas Glamann<br />Hellbrookstraße 42a<br />22305 Hamburg</p>
                                        </div>
                                        <div>
                                            <h2 class="text-xl font-semibold text-[#1a1a1a] mb-4">Streitschlichtung</h2>
                                            <p class="leading-relaxed">Die Europäische Kommission stellt eine Plattform zur Online-Streitbeilegung (OS) bereit: <a href="https://ec.europa.eu/consumers/odr/" target="_blank" rel="noopener noreferrer" class="text-[#0D7A5F] hover:underline">https://ec.europa.eu/consumers/odr/</a></p>
                                            <p class="mt-4 leading-relaxed">Wir sind nicht bereit oder verpflichtet, an Streitbeilegungsverfahren vor einer Verbraucherschlichtungsstelle teilzunehmen.</p>
                                        </div>
                                    </section>
                                ',
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
                'title' => 'Datenschutzerklärung',
                'type' => 'legal',
                'is_published' => true,
                'display_order' => 11,
                'content' => [
                    'version' => '1.0',
                    'type' => 'page',
                    'blocks' => [
                        [
                            'id' => 'datenschutz-content',
                            'type' => 'TextBlock',
                            'props' => [
                                'content' => '
                                    <section class="space-y-10">
                                        <div>
                                            <h2 class="text-xl font-semibold text-[#1a1a1a] mb-4">1. Datenschutz auf einen Blick</h2>
                                            <h3 class="text-lg font-medium text-[#1a1a1a] mb-2">Allgemeine Hinweise</h3>
                                            <p class="leading-relaxed">Die folgenden Hinweise geben einen einfachen Überblick darüber, was mit Ihren personenbezogenen Daten passiert, wenn Sie diese Website besuchen.</p>
                                        </div>
                                        <div>
                                            <h2 class="text-xl font-semibold text-[#1a1a1a] mb-4">2. Verantwortliche Stelle</h2>
                                            <div class="bg-[#f9faf9] rounded-xl p-6 leading-relaxed">
                                                <p class="font-medium text-[#1a1a1a]">Jonas Glamann</p>
                                                <p>musikfürfirmen.de</p>
                                                <p class="mt-2">Hellbrookstraße 42a</p>
                                                <p>22305 Hamburg</p>
                                                <p class="mt-2">E-Mail: <a href="mailto:kontakt@musikfuerfirmen.de" class="text-[#0D7A5F] hover:underline">kontakt@musikfuerfirmen.de</a></p>
                                            </div>
                                        </div>
                                        <div>
                                            <h2 class="text-xl font-semibold text-[#1a1a1a] mb-4">3. Datenerfassung auf dieser Website</h2>
                                            <h3 class="text-lg font-medium text-[#1a1a1a] mb-2">Cookies</h3>
                                            <p class="leading-relaxed">Unsere Website verwendet keine Tracking-Cookies. Es werden lediglich technisch notwendige Cookies verwendet.</p>
                                        </div>
                                        <div>
                                            <h2 class="text-xl font-semibold text-[#1a1a1a] mb-4">4. Ihre Rechte</h2>
                                            <p class="leading-relaxed">Sie haben jederzeit das Recht, unentgeltlich Auskunft über Herkunft, Empfänger und Zweck Ihrer gespeicherten personenbezogenen Daten zu erhalten.</p>
                                        </div>
                                    </section>
                                ',
                            ]
                        ],
                    ]
                ]
            ]
        );

        $this->command->info('✓ Legal pages (Impressum, Datenschutz) created successfully');
    }
}
