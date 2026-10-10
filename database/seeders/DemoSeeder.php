<?php

namespace Database\Seeders;

use App\Models\Event;
use App\Models\User;
use Illuminate\Database\Seeder;

/** Données de démonstration : php artisan db:seed --class=DemoSeeder */
class DemoSeeder extends Seeder
{
    public function run(): void
    {
        $organizer = User::updateOrCreate(
            ['phone' => '771112233'],
            ['name' => 'Promo Teranga', 'password' => 'password', 'role' => 'organizer']
        );

        $events = [
            ['Soirée Afrobeat à la Corniche', 'Dakar', 'Terrasse Ocean View, Corniche Ouest', 10, 3000, 300],
            ['Formation : lancer son e-commerce', 'Thiès', 'Salle des conférences, Chambre de commerce', 17, 5000, 60],
            ['Tournoi de navétanes - finale', 'Rufisque', 'Stade Mawade Wade', 24, 1000, 1500],
        ];

        foreach ($events as [$title, $city, $venue, $days, $price, $capacity]) {
            Event::firstOrCreate(['title' => $title], [
                'user_id' => $organizer->id,
                'description' => 'Événement de démonstration. Modifiez ou supprimez-le depuis votre espace organisateur.',
                'venue' => $venue,
                'city' => $city,
                'starts_at' => now()->addDays($days)->setTime(20, 0),
                'price' => $price,
                'capacity' => $capacity,
            ]);
        }
    }
}
