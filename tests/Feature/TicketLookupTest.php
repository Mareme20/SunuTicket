<?php

namespace Tests\Feature;

use App\Models\Event;
use App\Models\Order;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TicketLookupTest extends TestCase
{
    use RefreshDatabase;

    public function test_buyer_can_find_paid_tickets_by_email(): void
    {
        $organizer = User::create([
            'name' => 'Organizer',
            'phone' => '771234567',
            'email' => 'organizer@example.com',
            'password' => 'secret',
            'role' => 'organizer',
        ]);

        $event = Event::create([
            'user_id' => $organizer->id,
            'title' => 'Email Lookup Event',
            'venue' => 'Salle 1',
            'city' => 'Dakar',
            'starts_at' => now()->addDay(),
            'price' => 3000,
            'capacity' => 50,
            'is_published' => true,
        ]);

        $order = Order::create([
            'event_id' => $event->id,
            'buyer_name' => 'Awa Diop',
            'buyer_phone' => '771234567',
            'buyer_email' => 'Awa@example.com',
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
            'code' => 'email-lookup-code',
            'holder_name' => 'Awa Diop',
            'status' => 'valid',
        ]);

        $this->post(route('tickets.search'), ['lookup' => 'awa@example.com'])
            ->assertOk()
            ->assertSeeText('Email Lookup Event')
            ->assertSeeText('Voir mes billets');
    }
}
