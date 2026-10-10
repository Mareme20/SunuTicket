@extends('layouts.app')

@section('title', 'Tableau de bord Organisateur')

@section('content')
<div class="dashboard-header">
    <div>
        <h1>Tableau de bord</h1>
        <p style="color:var(--text-muted);font-size:0.95rem;margin:4px 0 0;">
            Bienvenue, <strong>{{ auth()->user()->name }}</strong>. Suivez vos ventes et gérez vos accès.
        </p>
    </div>
    <a class="btn btn-primary" href="{{ route('organizer.events.create') }}">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
            <line x1="12" y1="5" x2="12" y2="19"></line>
            <line x1="5" y1="12" x2="19" y2="12"></line>
        </svg>
        <span>Créer un événement</span>
    </a>
</div>

<!-- Cartes Statistiques KPI -->
<dl class="stat-grid">
    <div class="stat-card">
        <div class="stat-info">
            <dt class="stat-label">Billets vendus</dt>
            <dd class="stat-value">{{ number_format($totals['tickets'], 0, ',', ' ') }}</dd>
        </div>
        <div class="stat-icon">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <rect x="3" y="3" width="18" height="18" rx="2"></rect>
                <path d="M7 7h.01M7 12h.01M7 17h.01M12 7h.01M12 12h.01M12 17h.01M17 7h.01M17 12h.01M17 17h.01"></path>
            </svg>
        </div>
    </div>

    <div class="stat-card featured">
        <div class="stat-info">
            <dt class="stat-label">Recettes encaissées</dt>
            <dd class="stat-value">{{ fcfa($totals['revenue']) }}</dd>
        </div>
        <div class="stat-icon">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <line x1="12" y1="1" x2="12" y2="23"></line>
                <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path>
            </svg>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-info">
            <dt class="stat-label">Événements actifs</dt>
            <dd class="stat-value">{{ $totals['events'] }}</dd>
        </div>
        <div class="stat-icon">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                <line x1="16" y1="2" x2="16" y2="6"></line>
                <line x1="8" y1="2" x2="8" y2="6"></line>
                <line x1="3" y1="10" x2="21" y2="10"></line>
            </svg>
        </div>
    </div>
</dl>

<div class="section-header" style="margin-top:40px;">
    <div>
        <h2>Mes événements</h2>
        <p class="section-subtitle">Gérez vos billetteries en temps réel et validez les entrées au scanner.</p>
    </div>
</div>

@if($events->isEmpty())
    <div class="empty-state">
        <div class="empty-icon">
            <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                <line x1="16" y1="2" x2="16" y2="6"></line>
                <line x1="8" y1="2" x2="8" y2="6"></line>
                <line x1="3" y1="10" x2="21" y2="10"></line>
            </svg>
        </div>
        <h3>Vous n'avez pas encore d'événement</h3>
        <p>Publiez votre premier concert, soirée ou conférence pour commencer à vendre des billets avec Wave et Orange Money.</p>
        <a class="btn btn-primary" href="{{ route('organizer.events.create') }}">Créer mon premier événement</a>
    </div>
@else
    <div class="org-event-list">
        @foreach($events as $event)
            @php $pct = $event->capacity ? min(100, round($event->tickets_count / $event->capacity * 100)) : 0; @endphp
            <div class="org-event-item">
                <div class="org-event-info">
                    <div style="display:flex;align-items:center;gap:10px;margin-bottom:4px;">
                        <a class="org-event-title" href="{{ route('organizer.events.show', $event) }}">
                            {{ $event->title }}
                        </a>
                        @if($event->is_published)
                            <span class="badge-pill paid" style="font-size:0.72rem;">En ligne</span>
                        @else
                            <span class="badge-pill pending" style="font-size:0.72rem;">Non publié</span>
                        @endif
                    </div>

                    <div class="org-event-meta">
                        <span>{{ $event->starts_at->translatedFormat('j F Y \à H\hi') }}</span>
                        <span>·</span>
                        <span>{{ $event->venue }}, {{ $event->city }}</span>
                    </div>

                    <!-- Barre de progression des ventes -->
                    <div class="progress-bar-wrap" title="{{ $pct }}% des places vendues">
                        <div class="progress-bar-fill" style="width: {{ $pct }}%;"></div>
                    </div>

                    <div class="org-event-stats">
                        <strong style="color:var(--teal-700);">{{ $event->tickets_count }} / {{ $event->capacity }}</strong> billets vendus ({{ $pct }}%)
                        <span>·</span>
                        <span style="color:var(--text-muted);">Recettes :</span>
                        <strong>{{ fcfa((int) $event->revenue) }}</strong>
                    </div>
                </div>

                <div class="org-event-actions">
                    <a class="btn btn-primary btn-sm" href="{{ route('organizer.scan', $event) }}" title="Scanner les billets à l'entrée">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M3 7V5a2 2 0 0 1 2-2h2"></path>
                            <path d="M17 3h2a2 2 0 0 1 2 2v2"></path>
                            <path d="M21 17v2a2 2 0 0 1-2 2h-2"></path>
                            <path d="M7 21H5a2 2 0 0 1-2-2v-2"></path>
                        </svg>
                        <span>Scanner</span>
                    </a>

                    <a class="btn btn-ghost btn-sm" href="{{ route('organizer.events.show', $event) }}">
                        <span>Détails & Ventes</span>
                    </a>

                    <a class="btn btn-ghost btn-sm" href="{{ route('organizer.events.edit', $event) }}">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M12 20h9"></path>
                            <path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"></path>
                        </svg>
                        <span>Modifier</span>
                    </a>
                </div>
            </div>
        @endforeach
    </div>
@endif
@endsection
