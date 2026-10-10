<?php

namespace App\Http\Controllers\Organizer;

use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function index()
    {
        $events = auth()->user()->events()
            ->withCount('tickets')
            ->withSum(['orders as revenue' => fn ($q) => $q->where('status', 'paid')], 'subtotal')
            ->latest('starts_at')
            ->get();

        $totals = [
            'events' => $events->count(),
            'tickets' => $events->sum('tickets_count'),
            'revenue' => (int) $events->sum('revenue'),
        ];

        return view('organizer.dashboard', compact('events', 'totals'));
    }
}
