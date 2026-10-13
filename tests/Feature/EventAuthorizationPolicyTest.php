<?php

namespace Tests\Feature;

use App\Models\Event;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EventAuthorizationPolicyTest extends TestCase
{
    use RefreshDatabase;

    public function test_organizer_cannot_access_another_organizers_event_backoffice(): void
    {
        $owner = User::create([
            'name' => 'Owner',
            'phone' => '771111111',
            'email' => 'owner@example.com',
            'password' => 'secret',
            'role' => 'organizer',
        ]);

        $intruder = User::create([
            'name' => 'Other Organizer',
            'phone' => '772222222',
            'email' => 'other@example.com',
            'password' => 'secret',
            'role' => 'organizer',
        ]);

        $event = Event::create([
            'user_id' => $owner->id,
            'title' => 'Private Backoffice Event',
            'venue' => 'Salle privée',
            'city' => 'Dakar',
            'starts_at' => now()->addDay(),
            'price' => 3000,
            'capacity' => 50,
            'is_published' => true,
        ]);

        $this->actingAs($intruder)
            ->get(route('organizer.events.show', $event))
            ->assertForbidden();
    }
}
