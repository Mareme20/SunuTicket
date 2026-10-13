<?php

namespace Tests\Feature;

use App\Models\Event;
use App\Models\Order;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminDashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_redirected_from_admin_dashboard(): void
    {
        $this->get(route('admin.index'))
            ->assertRedirect(route('login'));
    }

    public function test_regular_organizer_cannot_access_admin_dashboard(): void
    {
        $organizer = User::create([
            'name' => 'Organisateur Solo',
            'phone' => '771234500',
            'email' => 'solo@example.com',
            'password' => 'secret',
            'role' => 'organizer',
        ]);

        $this->actingAs($organizer)
            ->get(route('admin.index'))
            ->assertForbidden();
    }

    public function test_admin_can_access_dashboard_with_all_metrics_and_charts(): void
    {
        $admin = User::create([
            'name' => 'Admin Boss',
            'phone' => '779998877',
            'email' => 'admin@sunuticket.sn',
            'password' => 'secret',
            'role' => 'admin',
        ]);

        $organizer = User::create([
            'name' => 'Dakar Events',
            'phone' => '772223344',
            'email' => 'contact@dakarevents.sn',
            'password' => 'secret',
            'role' => 'organizer',
        ]);

        $event = Event::create([
            'user_id' => $organizer->id,
            'title' => 'Gala Acoustique Dakar',
            'venue' => 'Grand Théâtre National',
            'city' => 'Dakar',
            'starts_at' => now()->addDays(5),
            'price' => 5000,
            'capacity' => 200,
            'is_published' => true,
        ]);

        $order = Order::create([
            'event_id' => $event->id,
            'buyer_name' => 'Moussa Ndiaye',
            'buyer_phone' => '776543210',
            'buyer_email' => 'moussa@example.com',
            'quantity' => 2,
            'unit_price' => 5000,
            'subtotal' => 10000,
            'commission' => 700,
            'total' => 10700,
            'gateway_fee' => 321,
            'admin_net' => 379,
            'status' => 'paid',
            'paid_at' => now(),
        ]);

        $ticket1 = Ticket::create([
            'order_id' => $order->id,
            'event_id' => $event->id,
            'code' => 'TICK-TEST-001',
            'holder_name' => 'Moussa Ndiaye',
            'status' => 'used',
            'used_at' => now(),
            'used_by' => $admin->id,
        ]);

        $ticket2 = Ticket::create([
            'order_id' => $order->id,
            'event_id' => $event->id,
            'code' => 'TICK-TEST-002',
            'holder_name' => 'Moussa Ndiaye',
            'status' => 'valid',
        ]);

        $response = $this->actingAs($admin)->get(route('admin.index'));

        $response->assertOk();
        $response->assertViewIs('admin.index');

        // Vérifier les données passées à la vue
        $response->assertViewHas('stats');
        $response->assertViewHas('chartDaily');
        $response->assertViewHas('chartStatus');
        $response->assertViewHas('chartTopEvents');
        $response->assertViewHas('events');
        $response->assertViewHas('payments');

        // Vérifier la présence des sections clés et des graphiques
        $response->assertSee('Console d&#039;Administration SunuTicket', false);
        $response->assertSee('Bénéfice Net Plateforme');
        $response->assertSee('Volume d&#039;Affaires Brut', false);
        $response->assertSee('revenueTrendChart');
        $response->assertSee('orderStatusChart');
        $response->assertSee('topEventsChart');
        $response->assertSee('scanRatioChart');
        $response->assertSee('Gala Acoustique Dakar');
        $response->assertSee('Moussa Ndiaye');
    }
}
