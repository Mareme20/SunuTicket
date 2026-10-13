<?php

namespace App\Http\Controllers\Organizer;

use App\Http\Controllers\Controller;
use App\Models\Event;

class DashboardController extends Controller
{
    public function index()
    {
        $events = auth()->user()->events()
            ->withCount('tickets')
            ->withCount(['tickets as used_tickets_count' => fn ($q) => $q->where('status', 'used')])
            ->withSum(['orders as revenue' => fn ($q) => $q->where('status', 'paid')], 'subtotal')
            ->latest('starts_at')
            ->get();

        $upcomingEvents = $events
            ->filter(fn (Event $event) => $event->starts_at->isFuture())
            ->sortBy('starts_at')
            ->values();

        $totals = [
            'events' => $events->count(),
            'tickets' => $events->sum('tickets_count'),
            'entered' => $events->sum('used_tickets_count'),
            'revenue' => (int) $events->sum('revenue'),
            'upcoming' => $upcomingEvents->count(),
        ];

        $nextEvent = $upcomingEvents->first();
        $attentionEvents = $upcomingEvents
            ->filter(function (Event $event) {
                $soldRate = $event->capacity > 0 ? ($event->tickets_count / $event->capacity) : 0;

                return $event->starts_at->lte(now()->addDays(7)) || $soldRate >= 0.8 || ! $event->is_published;
            })
            ->take(4);

        return view('organizer.dashboard', compact('events', 'totals', 'nextEvent', 'attentionEvents'));
    }
}
