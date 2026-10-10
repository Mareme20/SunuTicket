<?php

namespace Tests\Feature;

use App\Models\Event;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrganizerPromoCodeManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_organizer_can_create_and_list_promo_codes_for_event(): void
    {
        $user = User::create([
            'name' => 'Promo Organizer',
            'phone' => '771234567',
            'email' => 'promo@example.com',
            'password' => 'secret',
            'role' => 'organizer',
        ]);

        $event = Event::create([
            'user_id' => $user->id,
            'title' => 'Event with Promo',
            'venue' => 'Salle 2',
            'city' => 'Dakar',
            'starts_at' => now()->addDay(),
            'price' => 3000,
            'capacity' => 50,
            'is_published' => true,
        ]);

        $this->actingAs($user)
            ->post(route('organizer.promo-codes.store', $event), [
                'code' => 'TERANGA10',
                'type' => 'percent',
                'value' => 10,
                'usage_limit' => 5,
                'starts_at' => now()->subDay()->format('Y-m-d\TH:i'),
                'expires_at' => now()->addMonth()->format('Y-m-d\TH:i'),
                'is_active' => true,
            ])
            ->assertRedirect(route('organizer.events.show', $event));

        $this->actingAs($user)
            ->get(route('organizer.events.show', $event))
            ->assertOk()
            ->assertSeeText('TERANGA10');
    }
}
