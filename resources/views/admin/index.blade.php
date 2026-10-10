@extends('layouts.app')

@section('title', 'Administration de la plateforme')

@section('content')

<div class="dashboard-header">
    <div>
        <h1 style="font-size:2.1rem;color:var(--navy-950);">Administration SunuTicket</h1>
        <p style="color:var(--text-muted);font-size:0.95rem;margin:4px 0 0;">
            Supervision globale, commissions et flux de transactions financières.
        </p>
    </div>
</div>

<!-- Statistiques financières -->
<dl class="stat-grid">
    <div class="stat-card featured">
        <div class="stat-info">
            <dt class="stat-label">Bénéfice Net Plateforme</dt>
            <dd class="stat-value">{{ fcfa($stats['net']) }}</dd>
        </div>
        <div class="stat-icon">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <polyline points="23 6 13.5 15.5 8.5 10.5 1 18"></polyline>
                <polyline points="17 6 23 6 23 12"></polyline>
            </svg>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-info">
            <dt class="stat-label">Commissions brutes (7%)</dt>
            <dd class="stat-value" style="color:var(--teal-700);">{{ fcfa($stats['commission']) }}</dd>
        </div>
        <div class="stat-icon">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <circle cx="12" cy="12" r="10"></circle>
                <path d="M16 8h-6a2 2 0 1 0 0 4h4a2 2 0 1 1 0 4H8"></path>
                <path d="M12 18V6"></path>
            </svg>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-info">
            <dt class="stat-label">Volume total encaissé</dt>
            <dd class="stat-value">{{ fcfa($stats['gross']) }}</dd>
        </div>
        <div class="stat-icon">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <rect x="2" y="4" width="20" height="16" rx="2"></rect>
                <line x1="6" y1="8" x2="6" y2="8"></line>
                <line x1="10" y1="8" x2="18" y2="8"></line>
            </svg>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-info">
            <dt class="stat-label">Billets émis</dt>
            <dd class="stat-value">{{ $stats['tickets'] }}</dd>
        </div>
        <div class="stat-icon">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <rect x="3" y="3" width="18" height="18" rx="2"></rect>
                <path d="M7 7h.01M7 12h.01M7 17h.01M12 7h.01M12 12h.01M12 17h.01M17 7h.01M17 12h.01M17 17h.01"></path>
            </svg>
        </div>
    </div>
</dl>

<!-- Table des Événements -->
<div class="section-header" style="margin-top:40px;">
    <div>
        <h2>Tous les événements de la plateforme</h2>
        <p class="section-subtitle">Supervision des événements créés par les organisateurs partenaires.</p>
    </div>
</div>

<div class="table-card">
    <div class="table-responsive">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Événement</th>
                    <th>Organisateur</th>
                    <th>Date</th>
                    <th>Billets</th>
                    <th>Recettes Org.</th>
                    <th>Statut</th>
                </tr>
            </thead>
            <tbody>
                @forelse($events as $event)
                    <tr>
                        <td>
                            <a href="{{ route('organizer.events.show', $event) }}" style="font-weight:700;color:var(--navy-950);">
                                {{ $event->title }}
                            </a>
                        </td>
                        <td>
                            <strong>{{ $event->organizer->name }}</strong><br>
                            <span style="color:var(--text-muted);font-size:0.85rem;">{{ \App\Support\Phone::pretty($event->organizer->phone) }}</span>
                        </td>
                        <td>{{ $event->starts_at->translatedFormat('j M Y') }}</td>
                        <td>
                            <span class="badge-pill" style="background:var(--bg-surface);color:var(--text-main);">
                                {{ $event->tickets_count }} / {{ $event->capacity }}
                            </span>
                        </td>
                        <td><strong>{{ fcfa((int) $event->revenue) }}</strong></td>
                        <td>
                            @if($event->is_published)
                                <span class="badge-pill paid">Publié</span>
                            @else
                                <span class="badge-pill pending">Brouillon</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" style="text-align:center;color:var(--text-muted);padding:30px;">Aucun événement enregistré.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="pagination-wrap">
    {{ $events->links('pagination.simple') }}
</div>

<!-- Table des Transactions Financières -->
<div class="section-header" style="margin-top:48px;">
    <div>
        <h2>Historique des paiements</h2>
        <p class="section-subtitle">Transactions Wave et Orange Money opérées via PayDunya.</p>
    </div>
</div>

<div class="table-card">
    <div class="table-responsive">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Acheteur</th>
                    <th>Événement</th>
                    <th>Qté</th>
                    <th>Total Payé</th>
                    <th>Commission (7%)</th>
                    <th>Net Plateforme</th>
                    <th>Statut</th>
                </tr>
            </thead>
            <tbody>
                @forelse($payments as $order)
                    <tr>
                        <td style="color:var(--text-muted);">{{ $order->created_at->translatedFormat('j M, H\hi') }}</td>
                        <td>
                            <strong>{{ $order->buyer_name }}</strong><br>
                            <span style="color:var(--text-muted);font-size:0.85rem;">{{ \App\Support\Phone::pretty($order->buyer_phone) }}</span>
                        </td>
                        <td style="max-width:220px;overflow:hidden;text-overflow:ellipsis;">{{ $order->event->title }}</td>
                        <td>{{ $order->quantity }}</td>
                        <td><strong>{{ fcfa($order->total) }}</strong></td>
                        <td style="color:var(--teal-700);">{{ fcfa($order->commission) }}</td>
                        <td>
                            <strong>{{ $order->status === 'paid' ? fcfa($order->admin_net) : '—' }}</strong>
                        </td>
                        <td>
                            <span class="badge-pill {{ $order->status }}">
                                {{ $order->statusLabel() }}
                            </span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" style="text-align:center;color:var(--text-muted);padding:30px;">Aucun paiement enregistré.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection
