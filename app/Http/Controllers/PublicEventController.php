<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;

class PublicEventController extends Controller
{
    public function index(Request $request)
    {
        $search = trim((string) $request->query('q', ''));
        $category = trim((string) $request->query('category', ''));
        $dateFilter = trim((string) $request->query('date', ''));

        $events = Event::onSale()
            ->withCount('tickets')
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('title', 'like', "%{$search}%")
                        ->orWhere('city', 'like', "%{$search}%")
                        ->orWhere('venue', 'like', "%{$search}%");
                });
            })
            ->when($category !== '', fn ($query) => $query->where('category', $category))
            ->when($dateFilter === 'today', fn ($query) => $query->whereDate('starts_at', today()))
            ->when($dateFilter === 'weekend', fn ($query) => $query->whereBetween('starts_at', [now()->startOfWeek(), now()->endOfWeek()]))
            ->when($dateFilter === 'soon', fn ($query) => $query->where('starts_at', '<=', now()->addDays(14)))
            ->orderBy('starts_at')
            ->simplePaginate(12)
            ->withQueryString();

        return view('events.index', compact('events', 'search', 'category', 'dateFilter'));
    }

    public function show(Event $event)
    {
        abort_unless($event->is_published, 404);

        $remaining = $event->remaining();
        $onSale = $event->starts_at->isFuture() && $remaining > 0;
        $maxQty = max(1, min(config('sunuticket.max_per_order'), $remaining));

        return view('events.show', compact('event', 'remaining', 'onSale', 'maxQty'));
    }
}
