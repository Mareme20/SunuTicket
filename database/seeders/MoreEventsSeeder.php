<?php

namespace Database\Seeders;

use App\Models\Event;
use App\Models\User;
use App\Support\Phone;
use Illuminate\Database\Seeder;

class MoreEventsSeeder extends Seeder
{
    public function run(): void
    {
        $organizer = User::where('phone', Phone::normalize('77 111 22 33'))->first()
            ?? User::where('role', 'organizer')->first();

        if (! $organizer) {
            $organizer = User::create([
                'name' => 'Mamadou Faye',
                'phone' => Phone::normalize('77 111 22 33'),
                'email' => 'organisateur@sunuticket.sn',
                'password' => 'password',
                'role' => 'organizer',
            ]);
        }

        $events = [
            [
                'title' => 'Nuit du Mbalax Live',
                'description' => 'Une nuit 100% live avec des artistes locaux, danse et ambiance dakaroise.',
                'venue' => 'CICES Dakar',
                'city' => 'Dakar',
                'category' => 'concert',
                'starts_at' => now()->addDays(9)->setTime(22, 0),
                'price' => 4000,
                'capacity' => 600,
                'ticket_types' => [
                    ['name' => 'Standard', 'price' => 4000],
                    ['name' => 'Fan zone', 'price' => 8000],
                    ['name' => 'VIP', 'price' => 20000],
                ],
            ],
            [
                'title' => 'Comedy Night Dakar',
                'description' => 'Stand-up, sketchs et invités surprises pour une soirée humour.',
                'venue' => 'Théâtre Daniel Sorano',
                'city' => 'Dakar',
                'category' => 'spectacle',
                'starts_at' => now()->addDays(15)->setTime(20, 30),
                'price' => 3500,
                'capacity' => 350,
                'ticket_types' => [
                    ['name' => 'Balcon', 'price' => 3500],
                    ['name' => 'Orchestre', 'price' => 6000],
                ],
            ],
            [
                'title' => 'Salon Jeunes Entrepreneurs',
                'description' => 'Rencontres, pitchs, networking et ateliers pratiques pour les porteurs de projets.',
                'venue' => 'Place du Souvenir Africain',
                'city' => 'Dakar',
                'category' => 'conference',
                'starts_at' => now()->addDays(25)->setTime(10, 0),
                'price' => 0,
                'capacity' => 300,
                'ticket_types' => [],
            ],
            [
                'title' => 'Thiès Food Festival',
                'description' => 'Cuisine locale, stands, musique et dégustations familiales.',
                'venue' => 'Promenade des Thiessois',
                'city' => 'Thiès',
                'category' => 'festival',
                'starts_at' => now()->addDays(18)->setTime(16, 0),
                'price' => 2000,
                'capacity' => 800,
                'ticket_types' => [
                    ['name' => 'Entrée simple', 'price' => 2000],
                    ['name' => 'Pack dégustation', 'price' => 5000],
                ],
            ],
            [
                'title' => 'Saint-Louis Jazz Sunset',
                'description' => 'Concert jazz en bord de fleuve avec artistes sénégalais et invités internationaux.',
                'venue' => 'Quai des Arts',
                'city' => 'Saint-Louis',
                'category' => 'concert',
                'starts_at' => now()->addDays(30)->setTime(19, 30),
                'price' => 6000,
                'capacity' => 450,
                'ticket_types' => [
                    ['name' => 'Standard', 'price' => 6000],
                    ['name' => 'Premium', 'price' => 12000],
                ],
            ],
            [
                'title' => 'Kaolack Startup Meetup',
                'description' => 'Un rendez-vous business pour connecter startups, commerçants et investisseurs locaux.',
                'venue' => 'Centre culturel régional',
                'city' => 'Kaolack',
                'category' => 'conference',
                'starts_at' => now()->addDays(14)->setTime(17, 0),
                'price' => 1500,
                'capacity' => 120,
                'ticket_types' => [
                    ['name' => 'Participant', 'price' => 1500],
                    ['name' => 'Exposant', 'price' => 10000],
                ],
            ],
            [
                'title' => 'Ziguinchor Culture Week',
                'description' => 'Danse, patrimoine, artisanat et concerts pour célébrer la culture casamançaise.',
                'venue' => 'Alliance Franco-Sénégalaise',
                'city' => 'Ziguinchor',
                'category' => 'festival',
                'starts_at' => now()->addDays(35)->setTime(18, 0),
                'price' => 2500,
                'capacity' => 500,
                'ticket_types' => [
                    ['name' => 'Jour 1', 'price' => 2500],
                    ['name' => 'Pass semaine', 'price' => 10000],
                ],
            ],
            [
                'title' => 'Gala Solidaire Sunu École',
                'description' => 'Soirée caritative pour soutenir des fournitures scolaires et bourses locales.',
                'venue' => 'King Fahd Palace',
                'city' => 'Dakar',
                'category' => 'gala',
                'starts_at' => now()->addDays(40)->setTime(20, 0),
                'price' => 25000,
                'capacity' => 220,
                'ticket_types' => [
                    ['name' => 'Table soutien', 'price' => 25000],
                    ['name' => 'Table mécène', 'price' => 75000],
                ],
            ],
        ];

        foreach ($events as $event) {
            Event::updateOrCreate(
                ['title' => $event['title']],
                $event + [
                    'user_id' => $organizer->id,
                    'is_published' => true,
                ]
            );
        }
    }
}
