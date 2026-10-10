<?php

namespace Tests\Feature;

use App\Models\Event;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrganizerPublicProfileTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_profile_displays_organizer_info_and_events(): void
    {
        $organizer = User::create([
            'name' => 'Mamadou Faye',
            'phone' => '771234567',
            'email' => 'orga@example.com',
            'password' => 'secret',
            'role' => 'organizer',
        ]);

        Event::create([
            'user_id' => $organizer->id,
            'title' => 'Soirée Afrobeat Dakar',
            'description' => 'Une belle soirée en plein air.',
            'venue' => 'Plage de Yoff',
            'city' => 'Dakar',
            'starts_at' => now()->addDays(7),
            'price' => 5000,
            'capacity' => 200,
            'is_published' => true,
        ]);

        $response = $this->get(route('organizers.show', $organizer));

        $response->assertOk();
        $response->assertSeeText('Mamadou Faye');
        $response->assertSeeText('Soirée Afrobeat Dakar');
    }
}
