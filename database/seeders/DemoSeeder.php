<?php

namespace Database\Seeders;

use App\Models\Event;
use App\Models\Order;
use App\Models\PromoCode;
use App\Models\Ticket;
use App\Models\User;
use App\Services\Fees;
use App\Support\Phone;
use Illuminate\Database\Seeder;

/** Données de démonstration : php artisan db:seed --class=DemoSeeder */
class DemoSeeder extends Seeder
{
    public function run(): void
    {
        $organizer = User::updateOrCreate(
            ['phone' => Phone::normalize('77 111 22 33')],
            [
                'name' => 'Mamadou Faye',
                'email' => 'organisateur@sunuticket.sn',
                'password' => 'password',
                'role' => 'organizer',
            ]
        );

        $festival = $this->event($organizer, [
            'title' => 'Festival Sunu Sounds',
            'description' => "Une grande soirée live entre mbalax, afrobeat et amapiano. Billetterie de démonstration avec plusieurs tarifs, codes promo et ventes déjà validées.",
            'venue' => 'Grand Théâtre National',
            'city' => 'Dakar',
            'category' => 'festival',
            'starts_at' => now()->addDays(12)->setTime(21, 0),
            'price' => 5000,
            'capacity' => 250,
            'ticket_types' => [
                ['name' => 'Standard', 'price' => 5000],
                ['name' => 'VIP', 'price' => 15000],
            ],
            'is_published' => true,
        ]);

        $formation = $this->event($organizer, [
            'title' => 'Masterclass Business Digital',
            'description' => "Formation pratique pour vendre en ligne, structurer son offre et encaisser avec mobile money.",
            'venue' => 'Chambre de commerce',
            'city' => 'Thiès',
            'category' => 'formation',
            'starts_at' => now()->addDays(20)->setTime(9, 30),
            'price' => 7500,
            'capacity' => 80,
            'ticket_types' => [
                ['name' => 'Accès simple', 'price' => 7500],
                ['name' => 'Accès + support', 'price' => 12000],
            ],
            'is_published' => true,
        ]);

        $navetanes = $this->event($organizer, [
            'title' => 'Finale Navétanes Rufisque',
            'description' => "Finale locale avec contrôle d'accès par QR code et guichet mobile.",
            'venue' => 'Stade Ngalandou Diouf',
            'city' => 'Rufisque',
            'category' => 'sport',
            'starts_at' => now()->addDays(6)->setTime(17, 0),
            'price' => 1000,
            'capacity' => 1200,
            'ticket_types' => [
                ['name' => 'Gradins', 'price' => 1000],
                ['name' => 'Tribune', 'price' => 3000],
            ],
            'is_published' => true,
        ]);

        $gratuit = $this->event($organizer, [
            'title' => 'Rencontre Communauté Tech',
            'description' => "Événement gratuit pour tester les réservations sans paiement.",
            'venue' => 'Impact Hub Dakar',
            'city' => 'Dakar',
            'category' => 'conference',
            'starts_at' => now()->addDays(4)->setTime(18, 30),
            'price' => 0,
            'capacity' => 100,
            'ticket_types' => [],
            'is_published' => true,
        ]);

        PromoCode::updateOrCreate(
            ['code' => 'TERANGA10'],
            [
                'event_id' => $festival->id,
                'type' => 'percent',
                'value' => 10,
                'usage_limit' => 50,
                'used_count' => 1,
                'starts_at' => now()->subDay(),
                'expires_at' => now()->addDays(10),
                'is_active' => true,
            ]
        );

        PromoCode::updateOrCreate(
            ['code' => 'SUNU500'],
            [
                'event_id' => $formation->id,
                'type' => 'fixed',
                'value' => 500,
                'usage_limit' => 20,
                'used_count' => 0,
                'starts_at' => now()->subDay(),
                'expires_at' => now()->addDays(18),
                'is_active' => true,
            ]
        );

        $this->paidOrder($festival, [
            'buyer_name' => 'Awa Diop',
            'buyer_phone' => '77 654 32 10',
            'buyer_email' => 'awa.diop@example.com',
            'quantity' => 2,
            'ticket_type' => 'Standard',
            'unit_price' => 5000,
            'promo_code' => 'TERANGA10',
            'discount_amount' => 1000,
            'used_tickets' => 1,
            'paid_at' => now()->subDays(2),
        ]);

        $this->paidOrder($festival, [
            'buyer_name' => 'Cheikh Ndiaye',
            'buyer_phone' => '78 222 44 66',
            'buyer_email' => 'cheikh.ndiaye@example.com',
            'quantity' => 1,
            'ticket_type' => 'VIP',
            'unit_price' => 15000,
            'used_tickets' => 0,
            'paid_at' => now()->subDay(),
        ]);

        $this->paidOrder($formation, [
            'buyer_name' => 'Fatou Sarr',
            'buyer_phone' => '76 123 45 67',
            'buyer_email' => 'fatou.sarr@example.com',
            'quantity' => 3,
            'ticket_type' => 'Accès simple',
            'unit_price' => 7500,
            'used_tickets' => 0,
            'paid_at' => now()->subHours(12),
        ]);

        $this->paidOrder($navetanes, [
            'buyer_name' => 'Moussa Fall',
            'buyer_phone' => '70 888 77 66',
            'buyer_email' => 'moussa.fall@example.com',
            'quantity' => 5,
            'ticket_type' => 'Gradins',
            'unit_price' => 1000,
            'used_tickets' => 2,
            'paid_at' => now()->subHours(4),
        ]);

        $this->paidOrder($gratuit, [
            'buyer_name' => 'Ndeye Ba',
            'buyer_phone' => '77 900 10 20',
            'buyer_email' => 'ndeye.ba@example.com',
            'quantity' => 1,
            'ticket_type' => 'Standard',
            'unit_price' => 0,
            'used_tickets' => 0,
            'paid_at' => now()->subHours(3),
        ]);

        $this->pendingOrder($festival, [
            'buyer_name' => 'Ibrahima Kane',
            'buyer_phone' => '77 777 77 77',
            'buyer_email' => 'ibrahima.kane@example.com',
            'quantity' => 1,
            'ticket_type' => 'Standard',
            'unit_price' => 5000,
            'status' => 'pending',
            'created_at' => now()->subMinutes(8),
        ]);

        $this->pendingOrder($formation, [
            'buyer_name' => 'Khady Seck',
            'buyer_phone' => '78 111 11 11',
            'buyer_email' => 'khady.seck@example.com',
            'quantity' => 1,
            'ticket_type' => 'Accès + support',
            'unit_price' => 12000,
            'status' => 'expired',
            'created_at' => now()->subHours(2),
        ]);

        $this->pendingOrder($navetanes, [
            'buyer_name' => 'Oumar Sow',
            'buyer_phone' => '76 222 22 22',
            'buyer_email' => 'oumar.sow@example.com',
            'quantity' => 2,
            'ticket_type' => 'Tribune',
            'unit_price' => 3000,
            'status' => 'failed',
            'created_at' => now()->subDay(),
        ]);
    }

    private function event(User $organizer, array $data): Event
    {
        return Event::updateOrCreate(
            ['title' => $data['title']],
            $data + ['user_id' => $organizer->id]
        );
    }

    private function paidOrder(Event $event, array $data): Order
    {
        $usedTickets = (int) ($data['used_tickets'] ?? 0);
        unset($data['used_tickets']);

        $order = Order::create(array_merge(
            [
                'event_id' => $event->id,
                'buyer_phone' => Phone::normalize($data['buyer_phone']),
                'status' => 'paid',
                'paydunya_token' => 'demo_'.strtolower(str_replace(' ', '_', $data['buyer_name'])),
            ],
            $data,
            Fees::compute($data['unit_price'], $data['quantity'], $data['discount_amount'] ?? 0),
            [
                'buyer_phone' => Phone::normalize($data['buyer_phone']),
                'paid_at' => $data['paid_at'] ?? now(),
            ]
        ));

        for ($i = 1; $i <= $order->quantity; $i++) {
            Ticket::create([
                'order_id' => $order->id,
                'event_id' => $event->id,
                'code' => bin2hex(random_bytes(16)),
                'holder_name' => $order->buyer_name,
                'status' => $i <= $usedTickets ? 'used' : 'valid',
                'used_at' => $i <= $usedTickets ? now()->subMinutes(30 - $i) : null,
                'used_by' => $i <= $usedTickets ? $event->user_id : null,
            ]);
        }

        return $order;
    }

    private function pendingOrder(Event $event, array $data): Order
    {
        $createdAt = $data['created_at'] ?? now();
        unset($data['created_at']);

        $order = Order::create(array_merge(
            [
                'event_id' => $event->id,
                'buyer_phone' => Phone::normalize($data['buyer_phone']),
                'paydunya_token' => 'demo_pending_'.strtolower(str_replace(' ', '_', $data['buyer_name'])),
            ],
            $data,
            Fees::compute($data['unit_price'], $data['quantity']),
            [
                'buyer_phone' => Phone::normalize($data['buyer_phone']),
            ]
        ));

        $order->forceFill([
            'created_at' => $createdAt,
            'updated_at' => $createdAt,
        ])->save();

        return $order;
    }
}
