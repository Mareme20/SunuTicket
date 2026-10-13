@extends('layouts.app')

@section('title', 'Administration & Cockpit Financier · SunuTicket')

@section('content')

<div class="admin-cockpit">

    <!-- Hero Cockpit Header -->
    <div class="admin-hero-banner">
        <div class="admin-hero-content">
            <div class="admin-hero-badge-row">
                <span class="admin-status-pill">
                    <span class="admin-pulse-dot"></span>
                    Système opérationnel &middot; En direct
                </span>
                <span class="admin-mode-pill">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
                    </svg>
                    Passerelle PayDunya Active (Wave &amp; Orange Money)
                </span>
            </div>
            <h1 class="admin-hero-title">Console d'Administration SunuTicket</h1>
            <p class="admin-hero-sub">
                Supervision financière en temps réel, commissions de plateforme (7%), flux d'encaissement et contrôle d'accès aux événements.
            </p>
        </div>

        <div class="admin-hero-actions">
            @if($stats['stale_pending'] > 0)
                <form method="post" action="{{ route('admin.payments.expire') }}" style="margin:0;">
                    @csrf
                    <button class="btn btn-sm" style="background:#e59a0b;color:#090e23;font-weight:700;border:none;" type="submit" title="Libère les places bloquées par des commandes non finalisées">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
                            <circle cx="12" cy="12" r="10"></circle>
                            <polyline points="12 6 12 12 16 14"></polyline>
                        </svg>
                        <span>Purger {{ $stats['stale_pending'] }} panier(s) expiré(s)</span>
                    </button>
                </form>
            @endif

            <button class="btn btn-ghost btn-sm" style="background:rgba(255,255,255,0.12);color:#ffffff;border-color:rgba(255,255,255,0.25);" onclick="window.location.reload();">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <polyline points="23 4 23 10 17 10"></polyline>
                    <polyline points="1 20 1 14 7 14"></polyline>
                    <path d="M3.51 9a9 9 0 0 1 14.85-3.36L23 10M1 14l4.64 4.36A9 9 0 0 0 20.49 15"></path>
                </svg>
                <span>Actualiser</span>
            </button>

            <a class="btn btn-primary btn-sm" href="{{ route('home') }}" target="_blank">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path>
                    <polyline points="15 3 21 3 21 9"></polyline>
                    <line x1="10" y1="14" x2="21" y2="3"></line>
                </svg>
                <span>Voir le site public</span>
            </a>
        </div>
    </div>

    <!-- 4 Cartes Héro Financières -->
    <div class="admin-kpi-grid">
        <!-- KPI 1 : Bénéfice Net -->
        <div class="admin-kpi-card accent-navy">
            <div class="kpi-head">
                <span class="kpi-label">Bénéfice Net Plateforme</span>
                <div class="kpi-icon-wrap">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
                        <polyline points="23 6 13.5 15.5 8.5 10.5 1 18"></polyline>
                        <polyline points="17 6 23 6 23 12"></polyline>
                    </svg>
                </div>
            </div>
            <div>
                <div class="kpi-value">{{ fcfa($stats['net']) }}</div>
                <div class="kpi-subtext">
                    <span>Marge nette après frais PayDunya</span>
                    <span class="kpi-badge ok" style="background:rgba(52,211,153,0.2);color:#6ee7b7;">Net encaissé</span>
                </div>
            </div>
        </div>

        <!-- KPI 2 : Volume Brut Encaissé -->
        <div class="admin-kpi-card accent-emerald">
            <div class="kpi-head">
                <span class="kpi-label">Volume d'Affaires Brut</span>
                <div class="kpi-icon-wrap">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <rect x="2" y="4" width="20" height="16" rx="2"></rect>
                        <line x1="6" y1="8" x2="6" y2="8"></line>
                        <line x1="10" y1="8" x2="18" y2="8"></line>
                    </svg>
                </div>
            </div>
            <div>
                <div class="kpi-value" style="color:#059669;">{{ fcfa($stats['gross']) }}</div>
                <div class="kpi-subtext">
                    <span>{{ $stats['orders'] }} commande(s) payée(s)</span>
                    <span class="kpi-badge ok">{{ $stats['conversion_rate'] }}% succès</span>
                </div>
            </div>
        </div>

        <!-- KPI 3 : Commissions Plateforme -->
        <div class="admin-kpi-card accent-teal">
            <div class="kpi-head">
                <span class="kpi-label">Commissions Brutes (7%)</span>
                <div class="kpi-icon-wrap">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="10"></circle>
                        <path d="M16 8h-6a2 2 0 1 0 0 4h4a2 2 0 1 1 0 4H8"></path>
                        <path d="M12 18V6"></path>
                    </svg>
                </div>
            </div>
            <div>
                <div class="kpi-value" style="color:var(--teal-700);">{{ fcfa($stats['commission']) }}</div>
                <div class="kpi-subtext">
                    <span>Frais opérateur : {{ fcfa($stats['gateway']) }}</span>
                    <span class="kpi-badge info">Tarif 7%</span>
                </div>
            </div>
        </div>

        <!-- KPI 4 : Billets Émis & Validation -->
        <div class="admin-kpi-card accent-amber">
            <div class="kpi-head">
                <span class="kpi-label">Billets Émis &amp; Scans</span>
                <div class="kpi-icon-wrap">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <rect x="3" y="3" width="18" height="18" rx="2"></rect>
                        <path d="M7 7h.01M7 12h.01M7 17h.01M12 7h.01M12 12h.01M12 17h.01M17 7h.01M17 12h.01M17 17h.01"></path>
                    </svg>
                </div>
            </div>
            <div>
                <div class="kpi-value" style="color:#b45309;">{{ $stats['tickets'] }} <span style="font-size:1.1rem;font-weight:600;color:var(--text-muted);">billet(s)</span></div>
                <div class="kpi-subtext">
                    <span>{{ $stats['scans'] }} entrée(s) validée(s)</span>
                    <span class="kpi-badge warning">{{ $stats['scan_rate'] }}% scannés</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Mini-barre de pulsation opérationnelle -->
    <div class="admin-pulse-bar">
        <div class="pulse-pill-card">
            <div class="pulse-pill-icon green">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
                    <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                    <polyline points="22 4 12 14.01 9 11.01"></polyline>
                </svg>
            </div>
            <div class="pulse-pill-meta">
                <strong>{{ $stats['conversion_rate'] }}%</strong>
                <span>Taux de conversion commandes</span>
            </div>
        </div>

        <div class="pulse-pill-card">
            <div class="pulse-pill-icon blue">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
                    <line x1="12" y1="1" x2="12" y2="23"></line>
                    <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path>
                </svg>
            </div>
            <div class="pulse-pill-meta">
                <strong>{{ fcfa($stats['avg_order_value']) }}</strong>
                <span>Panier moyen par commande</span>
            </div>
        </div>

        <div class="pulse-pill-card">
            <div class="pulse-pill-icon purple">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
                    <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                    <circle cx="9" cy="7" r="4"></circle>
                    <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                    <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                </svg>
            </div>
            <div class="pulse-pill-meta">
                <strong>{{ $stats['organizers_count'] }} org. &middot; {{ $stats['events_count'] }} évén.</strong>
                <span>{{ $stats['published_events_count'] }} événement(s) en ligne</span>
            </div>
        </div>

        <div class="pulse-pill-card">
            <div class="pulse-pill-icon orange">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
                    <circle cx="12" cy="12" r="10"></circle>
                    <line x1="12" y1="8" x2="12" y2="12"></line>
                    <line x1="12" y1="16" x2="12.01" y2="16"></line>
                </svg>
            </div>
            <div class="pulse-pill-meta">
                <strong>{{ $stats['stale_pending'] }} à purger &middot; {{ $stats['failed'] }} échec(s)</strong>
                <span>Surveillance transactions</span>
            </div>
        </div>
    </div>

    <!-- Section Graphiques Analytics Interactifs -->
    <div class="admin-charts-grid">
        <!-- Graphique 1 : Évolution des Recettes & Net (Courbe) -->
        <div class="chart-card">
            <div class="chart-card-header">
                <div>
                    <h2 class="chart-card-title">Évolution des Flux Financiers (14 jours)</h2>
                    <p class="chart-card-desc">Suivi journalier du volume brut encaissé et du bénéfice net SunuTicket.</p>
                </div>
                <div class="chart-toggle-group">
                    <button class="chart-toggle-btn active" id="btnToggleFinance" onclick="switchMainChart('finance')">Recettes (FCFA)</button>
                    <button class="chart-toggle-btn" id="btnToggleTickets" onclick="switchMainChart('tickets')">Volume Billets</button>
                </div>
            </div>

            <div class="chart-canvas-wrapper" style="height: 300px;">
                <canvas id="revenueTrendChart"></canvas>
            </div>
        </div>

        <!-- Graphique 2 : Répartition des Statuts de Commandes (Donut) -->
        <div class="chart-card">
            <div class="chart-card-header">
                <div>
                    <h2 class="chart-card-title">Santé des Transactions</h2>
                    <p class="chart-card-desc">Répartition par statut de commande sur l'ensemble de la plateforme.</p>
                </div>
                <span class="badge-pill paid" style="font-size:0.75rem;">{{ $stats['total_orders'] }} au total</span>
            </div>

            <div class="chart-canvas-wrapper" style="height: 220px;">
                <canvas id="orderStatusChart"></canvas>
            </div>

            <div class="donut-legend-grid">
                <div class="donut-legend-item">
                    <div class="donut-legend-left">
                        <span class="donut-legend-dot" style="background:#10b981;"></span>
                        <span>Payées</span>
                    </div>
                    <strong>{{ $stats['orders'] }}</strong>
                </div>
                <div class="donut-legend-item">
                    <div class="donut-legend-left">
                        <span class="donut-legend-dot" style="background:#f59e0b;"></span>
                        <span>En attente</span>
                    </div>
                    <strong>{{ $stats['pending'] }}</strong>
                </div>
                <div class="donut-legend-item">
                    <div class="donut-legend-left">
                        <span class="donut-legend-dot" style="background:#ef4444;"></span>
                        <span>Échouées</span>
                    </div>
                    <strong>{{ $stats['failed'] }}</strong>
                </div>
                <div class="donut-legend-item">
                    <div class="donut-legend-left">
                        <span class="donut-legend-dot" style="background:#94a3b8;"></span>
                        <span>Expirées</span>
                    </div>
                    <strong>{{ $stats['expired'] }}</strong>
                </div>
            </div>
        </div>
    </div>

    <!-- Deuxième rangée de graphiques : Top Événements & Taux de Scans -->
    <div class="admin-charts-grid-secondary">
        <!-- Graphique 3 : Top 5 Événements -->
        <div class="chart-card">
            <div class="chart-card-header">
                <div>
                    <h2 class="chart-card-title">Top Événements par Recettes</h2>
                    <p class="chart-card-desc">Les 5 événements ayant généré le plus de ventes sur SunuTicket.</p>
                </div>
                <span class="badge-pill" style="background:var(--bg-surface);color:var(--text-main);font-size:0.75rem;">Classement FCFA</span>
            </div>
            <div class="chart-canvas-wrapper" style="height: 240px;">
                <canvas id="topEventsChart"></canvas>
            </div>
        </div>

        <!-- Graphique 4 : Contrôle d'Accès & Admissions -->
        <div class="chart-card">
            <div class="chart-card-header">
                <div>
                    <h2 class="chart-card-title">Contrôle d'Accès &amp; Validation Entrées</h2>
                    <p class="chart-card-desc">Proportion des billets scannés aux guichets par rapport au total émis.</p>
                </div>
                <span class="badge-pill paid" style="font-size:0.75rem;">{{ $stats['scan_rate'] }}% validés</span>
            </div>
            <div class="chart-canvas-wrapper" style="height: 240px;">
                <canvas id="scanRatioChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Deck de Surveillance Opérationnelle en Direct (3 Colonnes) -->
    <div class="admin-surveillance-grid">
        <!-- 1. Paiements Bloquants Anciens -->
        <div class="surveillance-card">
            <div class="surveillance-header">
                <h3>
                    <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="#f59e0b" stroke-width="2.2">
                        <circle cx="12" cy="12" r="10"></circle>
                        <polyline points="12 6 12 12 16 14"></polyline>
                    </svg>
                    Paiements à purger
                </h3>
                <span class="badge-pill {{ $oldPendingPayments->isEmpty() ? 'paid' : 'pending' }}">
                    {{ $oldPendingPayments->count() }}
                </span>
            </div>
            @if($oldPendingPayments->isNotEmpty())
                <form method="post" action="{{ route('admin.payments.expire') }}" style="margin:0 0 10px;">
                    @csrf
                    <button class="btn btn-ghost btn-sm" style="width:100%;font-size:0.8rem;padding:6px 10px;" type="submit">Libérer les places expirées</button>
                </form>
            @endif
            <div class="surveillance-feed">
                @forelse($oldPendingPayments as $order)
                    <div class="surveillance-item">
                        <div>
                            <strong>{{ $order->buyer_name }}</strong>
                            <span>{{ $order->event->title }} &middot; expire {{ $order->expiresAt()->diffForHumans() }}</span>
                        </div>
                        <time>{{ $order->created_at->diffForHumans() }}</time>
                    </div>
                @empty
                    <p class="ops-empty" style="padding:14px 0;text-align:center;">Aucune commande ne bloque des places au-delà du délai.</p>
                @endforelse
            </div>
        </div>

        <!-- 2. Derniers Échecs de Paiement -->
        <div class="surveillance-card">
            <div class="surveillance-header">
                <h3>
                    <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="#ef4444" stroke-width="2.2">
                        <polygon points="7.86 2 16.14 2 22 7.86 22 16.14 16.14 22 7.86 22 2 16.14 2 7.86 7.86 2"></polygon>
                        <line x1="12" y1="8" x2="12" y2="12"></line>
                        <line x1="12" y1="16" x2="12.01" y2="16"></line>
                    </svg>
                    Derniers échecs
                </h3>
                <span class="badge-pill {{ $failedPayments->isEmpty() ? 'paid' : 'failed' }}">
                    {{ $failedPayments->count() }}
                </span>
            </div>
            <div class="surveillance-feed">
                @forelse($failedPayments as $order)
                    <div class="surveillance-item">
                        <div>
                            <strong>{{ $order->buyer_name }}</strong>
                            <span>{{ $order->event->title }} &middot; {{ fcfa($order->total) }}</span>
                        </div>
                        <time>{{ $order->updated_at->translatedFormat('j M H\hi') }}</time>
                    </div>
                @empty
                    <p class="ops-empty" style="padding:14px 0;text-align:center;">Aucun échec récent à examiner.</p>
                @endforelse
            </div>
        </div>

        <!-- 3. Scans Récents aux Guichets -->
        <div class="surveillance-card">
            <div class="surveillance-header">
                <h3>
                    <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="#10b981" stroke-width="2.2">
                        <polyline points="20 6 9 17 4 12"></polyline>
                    </svg>
                    Scans en direct
                </h3>
                <span class="badge-pill paid">{{ $recentScans->count() }}</span>
            </div>
            <div class="surveillance-feed">
                @forelse($recentScans as $ticket)
                    <div class="surveillance-item">
                        <div>
                            <strong>{{ $ticket->holder_name }}</strong>
                            <span>{{ $ticket->event->title }} &middot; Code {{ $ticket->shortCode() }}</span>
                        </div>
                        <time>{{ $ticket->used_at?->diffForHumans() ?? 'Récemment' }}</time>
                    </div>
                @empty
                    <p class="ops-empty" style="padding:14px 0;text-align:center;">Aucun billet scanné pour le moment.</p>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Section Gestion Tabulaire avec Recherche Instantanée -->
    <div class="admin-management-section">
        <div class="admin-table-nav">
            <div class="admin-tab-buttons">
                <button class="admin-tab-btn active" id="tabBtnEvents" onclick="switchTableTab('events')">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                        <line x1="16" y1="2" x2="16" y2="6"></line>
                        <line x1="8" y1="2" x2="8" y2="6"></line>
                        <line x1="3" y1="10" x2="21" y2="10"></line>
                    </svg>
                    <span>Événements de la Plateforme</span>
                    <span class="tab-count">{{ $stats['events_count'] }}</span>
                </button>

                <button class="admin-tab-btn" id="tabBtnPayments" onclick="switchTableTab('payments')">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <rect x="1" y="4" width="22" height="16" rx="2" ry="2"></rect>
                        <line x1="1" y1="10" x2="23" y2="10"></line>
                    </svg>
                    <span>Historique des Transactions</span>
                    <span class="tab-count">{{ $stats['total_orders'] }}</span>
                </button>
            </div>

            <div class="admin-search-wrapper">
                <svg class="admin-search-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="11" cy="11" r="8"></circle>
                    <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                </svg>
                <input class="admin-search-input" id="adminInstantFilter" type="text" placeholder="Filtrer par nom, téléphone, statut..." oninput="filterAdminTables(this.value)">
            </div>
        </div>

        <!-- Onglet 1 : Tableau des Événements -->
        <div id="tabContentEvents">
            <div class="table-responsive">
                <table class="data-table" id="tableEvents">
                    <thead>
                        <tr>
                            <th>Événement</th>
                            <th>Organisateur</th>
                            <th>Date &amp; Lieu</th>
                            <th>Billets Vendus</th>
                            <th>Scannés</th>
                            <th>Recettes Org.</th>
                            <th>Net Plateforme</th>
                            <th>Statut</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($events as $event)
                            @php
                                $fillPct = $event->capacity ? min(100, round(($event->tickets_count / $event->capacity) * 100)) : 0;
                            @endphp
                            <tr class="searchable-row">
                                <td>
                                    <a href="{{ route('organizer.events.show', $event) }}" style="font-weight:700;color:var(--navy-950);">
                                        {{ $event->title }}
                                    </a>
                                </td>
                                <td>
                                    <strong>{{ $event->organizer->name }}</strong><br>
                                    <a href="tel:{{ $event->organizer->phone }}" style="color:var(--text-muted);font-size:0.84rem;text-decoration:none;">
                                        {{ \App\Support\Phone::pretty($event->organizer->phone) }}
                                    </a>
                                </td>
                                <td>
                                    <span style="font-weight:600;">{{ $event->starts_at->translatedFormat('j M Y') }}</span><br>
                                    <span style="color:var(--text-muted);font-size:0.82rem;">{{ $event->city }}</span>
                                </td>
                                <td>
                                    <div style="display:flex;align-items:center;gap:8px;">
                                        <div class="progress-bar-wrap" style="width:75px;height:6px;margin:0;" title="{{ $fillPct }}% réservé">
                                            <div class="progress-bar-fill" style="width: {{ $fillPct }}%;"></div>
                                        </div>
                                        <span style="font-weight:700;font-size:0.86rem;">
                                            {{ $event->tickets_count }} <span style="font-weight:400;color:var(--text-muted);">/ {{ $event->capacity }}</span>
                                        </span>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge-pill" style="background:var(--bg-surface);color:var(--navy-950);">
                                        {{ $event->used_tickets_count }}
                                    </span>
                                </td>
                                <td><strong>{{ fcfa((int) $event->revenue) }}</strong></td>
                                <td><strong style="color:var(--teal-700);">{{ fcfa((int) $event->platform_net) }}</strong></td>
                                <td>
                                    @if($event->is_published)
                                        <span class="status-badge-modern paid">
                                            <span class="badge-dot"></span>
                                            Publié
                                        </span>
                                    @else
                                        <span class="status-badge-modern pending">
                                            <span class="badge-dot"></span>
                                            Brouillon
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    <div style="display:flex;gap:6px;">
                                        <a class="btn btn-ghost btn-sm" href="{{ route('organizer.events.show', $event) }}" title="Voir les détails de l'événement" style="padding:4px 8px;">
                                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                                <circle cx="12" cy="12" r="3"></circle>
                                            </svg>
                                        </a>
                                        <a class="btn btn-ghost btn-sm" href="{{ route('events.show', $event) }}" target="_blank" title="Voir la page publique" style="padding:4px 8px;">
                                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path>
                                                <polyline points="15 3 21 3 21 9"></polyline>
                                                <line x1="10" y1="14" x2="21" y2="3"></line>
                                            </svg>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" style="text-align:center;color:var(--text-muted);padding:30px;">Aucun événement enregistré.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="pagination-wrap">
                {{ $events->links('pagination.simple') }}
            </div>
        </div>

        <!-- Onglet 2 : Tableau des Transactions Financières -->
        <div id="tabContentPayments" style="display:none;">
            <div class="table-responsive">
                <table class="data-table" id="tablePayments">
                    <thead>
                        <tr>
                            <th>Date &amp; Heure</th>
                            <th>Référence</th>
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
                            <tr class="searchable-row">
                                <td style="color:var(--text-muted);">{{ $order->created_at->translatedFormat('j M Y, H\hi') }}</td>
                                <td>
                                    <code style="font-size:0.75rem;padding:3px 6px;background:var(--bg-surface);border-radius:4px;color:var(--navy-900);">
                                        {{ substr($order->reference, 0, 8) }}...
                                    </code>
                                </td>
                                <td>
                                    <strong>{{ $order->buyer_name }}</strong><br>
                                    <a href="tel:{{ $order->buyer_phone }}" style="color:var(--text-muted);font-size:0.84rem;text-decoration:none;">
                                        {{ \App\Support\Phone::pretty($order->buyer_phone) }}
                                    </a>
                                </td>
                                <td style="max-width:200px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;" title="{{ $order->event->title }}">
                                    {{ $order->event->title }}
                                </td>
                                <td>
                                    <span class="badge-pill" style="background:var(--bg-surface);color:var(--navy-950);">
                                        {{ $order->quantity }}
                                    </span>
                                </td>
                                <td><strong>{{ fcfa($order->total) }}</strong></td>
                                <td style="color:var(--teal-700);">{{ fcfa($order->commission) }}</td>
                                <td>
                                    <strong>{{ $order->status === 'paid' ? fcfa($order->admin_net) : '—' }}</strong>
                                </td>
                                <td>
                                    @if($order->status === 'paid')
                                        <span class="status-badge-modern paid">
                                            <span class="badge-dot"></span>
                                            Payé
                                        </span>
                                    @elseif($order->status === 'pending')
                                        <span class="status-badge-modern pending">
                                            <span class="badge-dot"></span>
                                            En attente
                                        </span>
                                    @elseif($order->status === 'failed')
                                        <span class="status-badge-modern failed">
                                            <span class="badge-dot"></span>
                                            Échoué
                                        </span>
                                    @else
                                        <span class="status-badge-modern expired">
                                            <span class="badge-dot"></span>
                                            Expiré
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" style="text-align:center;color:var(--text-muted);padding:30px;">Aucun paiement enregistré.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="pagination-wrap">
                {{ $payments->links('pagination.simple') }}
            </div>
        </div>
    </div>

</div>

@endsection

@push('scripts')
<!-- Bibliothèque Chart.js (chargée localement avec fallback CDN) -->
<script src="{{ asset('js/chart.umd.min.js') }}"></script>
<script>
    if (typeof Chart === 'undefined') {
        const fallbackScript = document.createElement('script');
        fallbackScript.src = 'https://cdn.jsdelivr.net/npm/chart.js@4.4.4/dist/chart.umd.min.js';
        document.head.appendChild(fallbackScript);
    }
</script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    // --- 1. Graphique Évolution Financière & Volume (14 Jours) ---
    const dailyLabels = @json($chartDaily['labels']);
    const dailyGross = @json($chartDaily['gross']);
    const dailyNet = @json($chartDaily['net']);
    const dailyTickets = @json($chartDaily['tickets']);

    const ctxRevenue = document.getElementById('revenueTrendChart');
    if (ctxRevenue && typeof Chart !== 'undefined') {
        const gradientGross = ctxRevenue.getContext('2d').createLinearGradient(0, 0, 0, 280);
        gradientGross.addColorStop(0, 'rgba(23, 155, 155, 0.35)');
        gradientGross.addColorStop(1, 'rgba(23, 155, 155, 0.00)');

        const gradientNet = ctxRevenue.getContext('2d').createLinearGradient(0, 0, 0, 280);
        gradientNet.addColorStop(0, 'rgba(229, 154, 11, 0.28)');
        gradientNet.addColorStop(1, 'rgba(229, 154, 11, 0.00)');

        window.revenueChart = new Chart(ctxRevenue, {
            type: 'line',
            data: {
                labels: dailyLabels,
                datasets: [
                    {
                        label: 'Volume Brut Encaissé',
                        data: dailyGross,
                        borderColor: '#179b9b',
                        backgroundColor: gradientGross,
                        borderWidth: 2.5,
                        fill: true,
                        tension: 0.35,
                        pointBackgroundColor: '#179b9b',
                        pointBorderColor: '#ffffff',
                        pointHoverRadius: 6,
                        pointRadius: 3
                    },
                    {
                        label: 'Bénéfice Net SunuTicket',
                        data: dailyNet,
                        borderColor: '#e59a0b',
                        backgroundColor: gradientNet,
                        borderWidth: 2,
                        fill: true,
                        tension: 0.35,
                        pointBackgroundColor: '#e59a0b',
                        pointBorderColor: '#ffffff',
                        pointHoverRadius: 6,
                        pointRadius: 3
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                interaction: {
                    mode: 'index',
                    intersect: false
                },
                plugins: {
                    legend: {
                        position: 'top',
                        labels: {
                            boxWidth: 12,
                            font: { family: 'Plus Jakarta Sans', size: 12, weight: 600 },
                            color: '#475569'
                        }
                    },
                    tooltip: {
                        backgroundColor: '#090e23',
                        titleColor: '#ffffff',
                        bodyColor: '#e2e8f0',
                        borderColor: 'rgba(255,255,255,0.15)',
                        borderWidth: 1,
                        padding: 12,
                        cornerRadius: 8,
                        callbacks: {
                            label: function (context) {
                                return context.dataset.label + ': ' + new Intl.NumberFormat('fr-FR').format(context.parsed.y) + ' FCFA';
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { color: 'rgba(226, 232, 240, 0.6)', borderDash: [4, 4] },
                        ticks: {
                            font: { family: 'Plus Jakarta Sans', size: 11 },
                            color: '#64748b',
                            callback: function (val) {
                                return new Intl.NumberFormat('fr-FR').format(val) + ' F';
                            }
                        }
                    },
                    x: {
                        grid: { display: false },
                        ticks: {
                            font: { family: 'Plus Jakarta Sans', size: 11 },
                            color: '#64748b'
                        }
                    }
                }
            }
        });
    }

    // Toggle fonction pour le graphique principal
    window.switchMainChart = function (mode) {
        if (!window.revenueChart) return;
        const btnFinance = document.getElementById('btnToggleFinance');
        const btnTickets = document.getElementById('btnToggleTickets');

        if (mode === 'finance') {
            btnFinance.classList.add('active');
            btnTickets.classList.remove('active');

            window.revenueChart.data.datasets = [
                {
                    label: 'Volume Brut Encaissé',
                    data: dailyGross,
                    borderColor: '#179b9b',
                    backgroundColor: 'rgba(23, 155, 155, 0.15)',
                    borderWidth: 2.5,
                    fill: true,
                    tension: 0.35,
                    pointBackgroundColor: '#179b9b',
                    pointRadius: 3
                },
                {
                    label: 'Bénéfice Net SunuTicket',
                    data: dailyNet,
                    borderColor: '#e59a0b',
                    backgroundColor: 'rgba(229, 154, 11, 0.15)',
                    borderWidth: 2,
                    fill: true,
                    tension: 0.35,
                    pointBackgroundColor: '#e59a0b',
                    pointRadius: 3
                }
            ];
            window.revenueChart.options.plugins.tooltip.callbacks.label = function (context) {
                return context.dataset.label + ': ' + new Intl.NumberFormat('fr-FR').format(context.parsed.y) + ' FCFA';
            };
            window.revenueChart.options.scales.y.ticks.callback = function (val) {
                return new Intl.NumberFormat('fr-FR').format(val) + ' F';
            };
        } else {
            btnTickets.classList.add('active');
            btnFinance.classList.remove('active');

            window.revenueChart.data.datasets = [
                {
                    label: 'Billets Émis & Vendus',
                    data: dailyTickets,
                    borderColor: '#3b82f6',
                    backgroundColor: 'rgba(59, 130, 246, 0.18)',
                    borderWidth: 2.5,
                    fill: true,
                    tension: 0.35,
                    pointBackgroundColor: '#3b82f6',
                    pointRadius: 4
                }
            ];
            window.revenueChart.options.plugins.tooltip.callbacks.label = function (context) {
                return context.dataset.label + ': ' + context.parsed.y + ' billet(s)';
            };
            window.revenueChart.options.scales.y.ticks.callback = function (val) {
                return val + ' b.';
            };
        }
        window.revenueChart.update();
    };

    // --- 2. Graphique Donut : Répartition des Statuts de Paiement ---
    const ctxStatus = document.getElementById('orderStatusChart');
    if (ctxStatus && typeof Chart !== 'undefined') {
        const statusData = @json($chartStatus['data']);
        const statusLabels = @json($chartStatus['labels']);

        new Chart(ctxStatus, {
            type: 'doughnut',
            data: {
                labels: statusLabels,
                datasets: [{
                    data: statusData,
                    backgroundColor: ['#10b981', '#f59e0b', '#ef4444', '#94a3b8'],
                    borderWidth: 3,
                    borderColor: '#ffffff',
                    hoverOffset: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '70%',
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#090e23',
                        titleColor: '#ffffff',
                        bodyColor: '#e2e8f0',
                        cornerRadius: 8,
                        padding: 10,
                        callbacks: {
                            label: function (context) {
                                const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                const pct = total > 0 ? Math.round((context.raw / total) * 100) : 0;
                                return ' ' + context.label + ': ' + context.raw + ' (' + pct + '%)';
                            }
                        }
                    }
                }
            }
        });
    }

    // --- 3. Graphique Barres Horizontales : Top 5 Événements ---
    const ctxTop = document.getElementById('topEventsChart');
    if (ctxTop && typeof Chart !== 'undefined') {
        const topLabels = @json($chartTopEvents['labels']);
        const topRevenue = @json($chartTopEvents['revenue']);

        new Chart(ctxTop, {
            type: 'bar',
            data: {
                labels: topLabels.length ? topLabels : ['Aucun événement'],
                datasets: [{
                    label: 'Recettes (FCFA)',
                    data: topRevenue.length ? topRevenue : [0],
                    backgroundColor: [
                        'rgba(23, 155, 155, 0.85)',
                        'rgba(14, 165, 233, 0.85)',
                        'rgba(16, 185, 129, 0.85)',
                        'rgba(229, 154, 11, 0.85)',
                        'rgba(99, 102, 241, 0.85)'
                    ],
                    borderRadius: 6,
                    borderSkipped: false
                }]
            },
            options: {
                indexAxis: 'y',
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#090e23',
                        callbacks: {
                            label: function (ctx) {
                                return 'Recettes: ' + new Intl.NumberFormat('fr-FR').format(ctx.parsed.x) + ' FCFA';
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        beginAtZero: true,
                        grid: { color: 'rgba(226, 232, 240, 0.6)' },
                        ticks: {
                            font: { family: 'Plus Jakarta Sans', size: 10 },
                            callback: function (val) {
                                return new Intl.NumberFormat('fr-FR').format(val) + ' F';
                            }
                        }
                    },
                    y: {
                        grid: { display: false },
                        ticks: { font: { family: 'Plus Jakarta Sans', size: 11, weight: 600 } }
                    }
                }
            }
        });
    }

    // --- 4. Graphique Contrôle d'Accès : Billets Scannés vs En Attente ---
    const ctxScan = document.getElementById('scanRatioChart');
    if (ctxScan && typeof Chart !== 'undefined') {
        const totalTickets = {{ $stats['tickets'] }};
        const usedTickets = {{ $stats['scans'] }};
        const pendingTickets = Math.max(0, totalTickets - usedTickets);

        new Chart(ctxScan, {
            type: 'doughnut',
            data: {
                labels: ['Entrées Scannées (Validées)', 'Billets en Attente'],
                datasets: [{
                    data: totalTickets > 0 ? [usedTickets, pendingTickets] : [1, 0],
                    backgroundColor: totalTickets > 0 ? ['#10b981', '#e2e8f0'] : ['#e2e8f0', '#f1f5f9'],
                    borderWidth: 2,
                    borderColor: '#ffffff',
                    hoverOffset: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '72%',
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            boxWidth: 12,
                            font: { family: 'Plus Jakarta Sans', size: 11 }
                        }
                    },
                    tooltip: {
                        backgroundColor: '#090e23',
                        callbacks: {
                            label: function (context) {
                                if (totalTickets === 0) return ' Aucun billet émis';
                                const pct = Math.round((context.raw / totalTickets) * 100);
                                return ' ' + context.label + ': ' + context.raw + ' (' + pct + '%)';
                            }
                        }
                    }
                }
            }
        });
    }
});

// --- Gestion des Onglets et Filtre Instantané ---
function switchTableTab(tab) {
    const tabEvents = document.getElementById('tabContentEvents');
    const tabPayments = document.getElementById('tabContentPayments');
    const btnEvents = document.getElementById('tabBtnEvents');
    const btnPayments = document.getElementById('tabBtnPayments');

    if (tab === 'events') {
        tabEvents.style.display = 'block';
        tabPayments.style.display = 'none';
        btnEvents.classList.add('active');
        btnPayments.classList.remove('active');
    } else {
        tabEvents.style.display = 'none';
        tabPayments.style.display = 'block';
        btnEvents.classList.remove('active');
        btnPayments.classList.add('active');
    }
}

function filterAdminTables(query) {
    const term = (query || '').toLowerCase().trim();
    const rows = document.querySelectorAll('.searchable-row');

    rows.forEach(function (row) {
        if (!term) {
            row.style.display = '';
            return;
        }
        const text = row.textContent.toLowerCase();
        row.style.display = text.includes(term) ? '' : 'none';
    });
}
</script>
@endpush
