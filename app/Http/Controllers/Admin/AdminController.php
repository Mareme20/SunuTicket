<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Order;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Str;

class AdminController extends Controller
{
    public function index()
    {
        $paid = fn () => Order::where('status', 'paid');
        $stalePending = fn () => $this->stalePendingOrders();

        $stats = [
            'orders' => $paid()->count(),
            'tickets' => Ticket::count(),
            'gross' => (int) $paid()->sum('total'),
            'commission' => (int) $paid()->sum('commission'),
            'gateway' => (int) $paid()->sum('gateway_fee'),
            'net' => (int) $paid()->sum('admin_net'),
            'failed' => Order::where('status', 'failed')->count(),
            'stale_pending' => $stalePending()->count(),
            'expired' => Order::where('status', 'expired')->count(),
            'scans' => Ticket::where('status', 'used')->count(),
        ];

        // Calculs complémentaires pour les KPIs
        $totalOrders = Order::count();
        $pendingOrders = Order::where('status', 'pending')->count();
        $conversionRate = $totalOrders > 0 ? round(($stats['orders'] / $totalOrders) * 100, 1) : 0;
        $scanRate = $stats['tickets'] > 0 ? round(($stats['scans'] / $stats['tickets']) * 100, 1) : 0;
        $avgOrderValue = $stats['orders'] > 0 ? round($stats['gross'] / $stats['orders']) : 0;
        $organizersCount = User::where('role', 'organizer')->count();
        $eventsCount = Event::count();
        $publishedEventsCount = Event::where('is_published', true)->count();

        $stats['total_orders'] = $totalOrders;
        $stats['pending'] = $pendingOrders;
        $stats['conversion_rate'] = $conversionRate;
        $stats['scan_rate'] = $scanRate;
        $stats['avg_order_value'] = $avgOrderValue;
        $stats['organizers_count'] = $organizersCount;
        $stats['events_count'] = $eventsCount;
        $stats['published_events_count'] = $publishedEventsCount;

        // Données pour le graphique chronologique (14 derniers jours)
        $days = collect();
        for ($i = 13; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $days->put($date->format('Y-m-d'), [
                'label' => $date->translatedFormat('d M'),
                'gross' => 0,
                'net' => 0,
                'orders' => 0,
                'tickets' => 0,
            ]);
        }

        $recentPaidOrders = Order::where('status', 'paid')
            ->where('created_at', '>=', now()->subDays(13)->startOfDay())
            ->get(['total', 'admin_net', 'quantity', 'created_at']);

        foreach ($recentPaidOrders as $order) {
            $key = $order->created_at->format('Y-m-d');
            if ($days->has($key)) {
                $item = $days->get($key);
                $item['gross'] += (int) $order->total;
                $item['net'] += (int) $order->admin_net;
                $item['orders'] += 1;
                $item['tickets'] += (int) $order->quantity;
                $days->put($key, $item);
            }
        }

        $chartDaily = [
            'labels' => $days->pluck('label')->values()->all(),
            'gross' => $days->pluck('gross')->values()->all(),
            'net' => $days->pluck('net')->values()->all(),
            'tickets' => $days->pluck('tickets')->values()->all(),
            'orders' => $days->pluck('orders')->values()->all(),
        ];

        // Répartition des statuts de commande
        $chartStatus = [
            'labels' => ['Payés', 'En attente', 'Échoués', 'Expirés'],
            'data' => [
                $stats['orders'],
                $pendingOrders,
                $stats['failed'],
                $stats['expired'],
            ],
        ];

        // Top événements par rentabilité
        $topEvents = Event::withSum(['orders as revenue' => fn ($q) => $q->where('status', 'paid')], 'subtotal')
            ->withSum(['orders as platform_net' => fn ($q) => $q->where('status', 'paid')], 'admin_net')
            ->withCount('tickets')
            ->orderByDesc('revenue')
            ->limit(5)
            ->get();

        $chartTopEvents = [
            'labels' => $topEvents->map(fn ($e) => Str::limit($e->title, 20))->values()->all(),
            'revenue' => $topEvents->map(fn ($e) => (int) ($e->revenue ?? 0))->values()->all(),
            'platform_net' => $topEvents->map(fn ($e) => (int) ($e->platform_net ?? 0))->values()->all(),
            'tickets' => $topEvents->map(fn ($e) => (int) ($e->tickets_count ?? 0))->values()->all(),
        ];

        $events = Event::with('organizer')
            ->withCount('tickets')
            ->withCount(['tickets as used_tickets_count' => fn ($q) => $q->where('status', 'used')])
            ->withSum(['orders as revenue' => fn ($q) => $q->where('status', 'paid')], 'subtotal')
            ->withSum(['orders as platform_net' => fn ($q) => $q->where('status', 'paid')], 'admin_net')
            ->latest()
            ->simplePaginate(15, ['*'], 'events_page')
            ->withQueryString();

        $payments = Order::with('event')
            ->latest()
            ->simplePaginate(20, ['*'], 'payments_page')
            ->withQueryString();

        $failedPayments = Order::with('event')
            ->where('status', 'failed')
            ->latest()
            ->limit(8)
            ->get();

        $oldPendingPayments = $stalePending()
            ->with('event')
            ->oldest()
            ->limit(8)
            ->get();

        $recentScans = Ticket::with('event')
            ->where('status', 'used')
            ->latest('used_at')
            ->limit(10)
            ->get();

        return view('admin.index', compact(
            'stats',
            'events',
            'payments',
            'failedPayments',
            'oldPendingPayments',
            'recentScans',
            'chartDaily',
            'chartStatus',
            'chartTopEvents'
        ));
    }

    public function expirePending(): RedirectResponse
    {
        $expired = $this->stalePendingOrders()->update(['status' => 'expired']);

        return back()->with('status', $expired.' paiement(s) en attente expiré(s).');
    }

    private function stalePendingOrders()
    {
        return Order::where('status', 'pending')
            ->where('created_at', '<', now()->subMinutes(config('sunuticket.hold_minutes')));
    }
}
