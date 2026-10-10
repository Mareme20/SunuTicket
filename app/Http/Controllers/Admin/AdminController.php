<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Order;
use App\Models\Ticket;

class AdminController extends Controller
{
    public function index()
    {
        $paid = fn () => Order::where('status', 'paid');

        $stats = [
            'orders' => $paid()->count(),
            'tickets' => Ticket::count(),
            'gross' => (int) $paid()->sum('total'),
            'commission' => (int) $paid()->sum('commission'),
            'gateway' => (int) $paid()->sum('gateway_fee'),
            'net' => (int) $paid()->sum('admin_net'),
        ];

        $events = Event::with('organizer')
            ->withCount('tickets')
            ->withSum(['orders as revenue' => fn ($q) => $q->where('status', 'paid')], 'subtotal')
            ->latest()
            ->simplePaginate(15, ['*'], 'events_page')
            ->withQueryString();

        $payments = Order::with('event')
            ->latest()
            ->simplePaginate(20, ['*'], 'payments_page')
            ->withQueryString();

        return view('admin.index', compact('stats', 'events', 'payments'));
    }
}
