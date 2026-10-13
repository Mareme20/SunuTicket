<?php

namespace Tests\Feature;

use App\Models\Event;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class PublicEventFilteringTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_events_can_be_filtered_by_city(): void
    {
        $organizer = $this->organizer();

        Event::create($this->eventData($organizer->id, 'Concert Dakar', 'Dakar'));
        Event::create($this->eventData($organizer->id, 'Concert Thiès', 'Thiès'));

        $this->get(route('home', ['city' => 'Dakar']))
            ->assertOk()
            ->assertSeeText('Concert Dakar')
            ->assertDontSeeText('Concert Thiès');
    }

    public function test_weekend_filter_only_shows_next_weekend_events(): void
    {
        Carbon::setTestNow(Carbon::parse('2026-10-05 10:00:00'));

        $organizer = $this->organizer();

        Event::create($this->eventData($organizer->id, 'Saturday Event', 'Dakar', now()->next('Saturday')->setTime(20, 0)));
        Event::create($this->eventData($organizer->id, 'Monday Event', 'Dakar', now()->next('Monday')->setTime(20, 0)));

        $this->get(route('home', ['date' => 'weekend']))
            ->assertOk()
            ->assertSeeText('Saturday Event')
            ->assertDontSeeText('Monday Event');

        Carbon::setTestNow();
    }

    private function organizer(): User
    {
        return User::create([
            'name' => 'Public Filter Organizer',
            'phone' => '771234567',
            'email' => 'filter@example.com',
            'password' => 'secret',
            'role' => 'organizer',
        ]);
    }

    private function eventData(int $userId, string $title, string $city, ?Carbon $startsAt = null): array
    {
        return [
            'user_id' => $userId,
            'title' => $title,
            'venue' => 'Salle 1',
            'city' => $city,
            'starts_at' => $startsAt ?? now()->addDays(7),
            'price' => 3000,
            'capacity' => 50,
            'category' => 'concert',
            'is_published' => true,
        ];
    }
}
