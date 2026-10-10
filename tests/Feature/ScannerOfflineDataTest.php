<?php

namespace Tests\Feature;

use App\Models\Event;
use App\Models\Order;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ScannerOfflineDataTest extends TestCase
{
    use RefreshDatabase;

    public function test_organizer_can_fetch_offline_scanner_payload(): void
    {
        $user = User::create([
            'name' => 'Test Organizer',
            'phone' => '771234567',
            'email' => 'organizer@example.com',
            'password' => 'secret',
            'role' => 'organizer',
        ]);

        $event = Event::create([
            'user_id' => $user->id,
            'title' => 'Offline test event',
            'venue' => 'Salle 1',
            'city' => 'Dakar',
            'starts_at' => now()->addDay(),
            'price' => 3000,
            'capacity' => 50,
            'is_published' => true,
        ]);

        $order = Order::create([
            'event_id' => $event->id,
            'buyer_name' => 'Moussa Diop',
            'buyer_phone' => '771234567',
            'buyer_email' => 'moussa@example.com',
            'quantity' => 1,
            'unit_price' => 3000,
            'subtotal' => 3000,
            'commission' => 210,
            'total' => 3210,
            'gateway_fee' => 90,
            'admin_net' => 120,
            'status' => 'paid',
            'paid_at' => now(),
        ]);

        Ticket::create([
            'order_id' => $order->id,
            'event_id' => $event->id,
            'code' => 'ABCDEF12',
            'holder_name' => 'Moussa Diop',
            'status' => 'valid',
        ]);

        $response = $this->actingAs($user)
            ->getJson(route('organizer.scan.data', $event));

        $response->assertOk();
        $response->assertJsonPath('event.id', $event->id);
        $response->assertJsonFragment(['holder_name' => 'Moussa Diop']);
    }
}
