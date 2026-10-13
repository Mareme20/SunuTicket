<?php

namespace Tests\Feature;

use App\Models\Event;
use App\Models\Order;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

class ExpirePendingOrdersTest extends TestCase
{
    use RefreshDatabase;

    public function test_pending_orders_older_than_hold_window_are_expired(): void
    {
        $user = User::create([
            'name' => 'Organizer',
            'phone' => '771234567',
            'email' => 'organizer@example.com',
            'password' => 'secret',
            'role' => 'organizer',
        ]);

        $event = Event::create([
            'user_id' => $user->id,
            'title' => 'Expiration Test',
            'venue' => 'Salle 1',
            'city' => 'Dakar',
            'starts_at' => now()->addDay(),
            'price' => 3000,
            'capacity' => 50,
            'is_published' => true,
        ]);

        $oldOrder = Order::create($this->orderData($event->id, 'old@example.com') + [
            'status' => 'pending',
        ]);
        $oldOrder->forceFill([
            'created_at' => now()->subMinutes(config('sunuticket.hold_minutes') + 5),
        ])->save();

        $freshOrder = Order::create($this->orderData($event->id, 'fresh@example.com') + [
            'status' => 'pending',
        ]);

        Artisan::call('orders:expire');

        $this->assertSame('expired', $oldOrder->refresh()->status);
        $this->assertSame('pending', $freshOrder->refresh()->status);
    }

    public function test_admin_can_expire_stale_pending_orders_from_backoffice(): void
    {
        $admin = User::create([
            'name' => 'Admin',
            'phone' => '779999999',
            'email' => 'admin@example.com',
            'password' => 'secret',
            'role' => 'admin',
        ]);

        $organizer = User::create([
            'name' => 'Organizer',
            'phone' => '771234567',
            'email' => 'organizer@example.com',
            'password' => 'secret',
            'role' => 'organizer',
        ]);

        $event = Event::create([
            'user_id' => $organizer->id,
            'title' => 'Admin Expire Test',
            'venue' => 'Salle 1',
            'city' => 'Dakar',
            'starts_at' => now()->addDay(),
            'price' => 3000,
            'capacity' => 50,
            'is_published' => true,
        ]);

        $oldOrder = Order::create($this->orderData($event->id, 'old-admin@example.com') + [
            'status' => 'pending',
        ]);
        $oldOrder->forceFill([
            'created_at' => now()->subMinutes(config('sunuticket.hold_minutes') + 5),
        ])->save();

        $this->actingAs($admin)
            ->post(route('admin.payments.expire'))
            ->assertRedirect();

        $this->assertSame('expired', $oldOrder->refresh()->status);
    }

    private function orderData(int $eventId, string $email): array
    {
        return [
            'event_id' => $eventId,
            'buyer_name' => 'Awa Diop',
            'buyer_phone' => '771234567',
            'buyer_email' => $email,
            'quantity' => 1,
            'unit_price' => 3000,
            'subtotal' => 3000,
            'commission' => 210,
            'total' => 3210,
            'gateway_fee' => 90,
            'admin_net' => 120,
        ];
    }
}
