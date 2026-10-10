<?php

namespace Tests\Feature;

use App\Models\Event;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EventCategoryPersistenceTest extends TestCase
{
    use RefreshDatabase;

    public function test_event_category_can_be_saved_and_updated(): void
    {
        $user = User::create([
            'name' => 'Category Owner',
            'phone' => '770000001',
            'email' => 'owner@example.com',
            'password' => 'secret',
            'role' => 'organizer',
        ]);

        $event = Event::create([
            'user_id' => $user->id,
            'title' => 'Concert test',
            'venue' => 'Salle Test',
            'city' => 'Dakar',
            'starts_at' => now()->addDay(),
            'price' => 3000,
            'capacity' => 100,
            'category' => 'concert',
            'is_published' => true,
        ]);

        $event->update(['category' => 'festival']);

        $this->assertSame('festival', $event->fresh()->category);
    }
}
