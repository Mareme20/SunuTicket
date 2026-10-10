@extends('layouts.app')

@section('content')

<!-- Bannière d'ambiance vivante et chaleureuse -->
<section class="hero" style="padding: 44px 36px 38px;">
    <div class="hero-inner" style="max-width: 820px;">
        <span style="display:inline-flex;align-items:center;gap:8px;background:rgba(23,155,155,0.25);border:1px solid rgba(66,212,212,0.4);color:var(--teal-300);padding:5px 14px;border-radius:var(--radius-full);font-size:0.85rem;font-weight:700;margin-bottom:16px;">
            🇸🇳 Billetterie en ligne au Sénégal
        </span>

        <h1 style="font-size: clamp(2rem, 4vw, 2.9rem); line-height: 1.15; margin-bottom: 14px;">
            Vos pass & sorties au Sénégal, <span class="highlight">sans faire la queue</span>
        </h1>
        <p style="font-size: 1.1rem; line-height: 1.6; color: rgba(255, 255, 255, 0.9); margin-bottom: 26px; max-width: 660px;">
            Concerts, soirées, spectacles, festivals et navétanes. Payez en 30 secondes avec Wave ou Orange Money et recevez vos billets directement sur votre téléphone.
        </p>

        <!-- Barre de recherche rapide -->
        <form class="search-box" method="get" action="{{ route('home') }}">
            <svg class="search-icon" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
                <circle cx="11" cy="11" r="8"></circle>
                <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
            </svg>
            <input type="search"
                   name="q"
                   value="{{ $search }}"
                   placeholder="Quel événement cherchez-vous ? (ex: Afrobeat, Dakar, Thiès...)"
                   aria-label="Rechercher un événement">
            <button class="btn btn-primary" type="submit">
                <span>Trouver</span>
            </button>
        </form>

        <!-- Filtres rapides -->
        <div style="display:flex;align-items:center;gap:8px;margin-top:18px;flex-wrap:wrap;font-size:0.88rem;">
            <span style="color:rgba(255,255,255,0.7);font-weight:500;">Explorer :</span>
            <a class="tag-chip" href="{{ route('home') }}" style="{{ !$category && !$dateFilter ? 'background:rgba(255,255,255,0.3);font-weight:700;' : '' }}">Tout voir</a>
            <a class="tag-chip" href="{{ route('home', ['category' => 'concert']) }}" style="{{ $category === 'concert' ? 'background:rgba(255,255,255,0.3);font-weight:700;' : '' }}">🎵 Concert</a>
            <a class="tag-chip" href="{{ route('home', ['category' => 'festival']) }}" style="{{ $category === 'festival' ? 'background:rgba(255,255,255,0.3);font-weight:700;' : '' }}">🎉 Festival</a>
            <a class="tag-chip" href="{{ route('home', ['date' => 'today']) }}" style="{{ $dateFilter === 'today' ? 'background:rgba(255,255,255,0.3);font-weight:700;' : '' }}">Aujourd’hui</a>
            <a class="tag-chip" href="{{ route('home', ['date' => 'weekend']) }}" style="{{ $dateFilter === 'weekend' ? 'background:rgba(255,255,255,0.3);font-weight:700;' : '' }}">Ce week-end</a>
            <a class="tag-chip" href="{{ route('home', ['date' => 'soon']) }}" style="{{ $dateFilter === 'soon' ? 'background:rgba(255,255,255,0.3);font-weight:700;' : '' }}">Bientôt</a>
            @if($search || $category || $dateFilter)
                <a class="tag-chip" href="{{ route('home') }}" style="background:rgba(239,68,68,0.3);border-color:rgba(239,68,68,0.5);">✕ Réinitialiser</a>
            @endif
        </div>
    </div>
</section>

<!-- Barre des points forts concrète et utile (pas de bla-bla corporate) -->
<div style="display:flex;align-items:center;justify-content:space-between;gap:20px;background:#ffffff;border:1px solid var(--border);border-radius:var(--radius-lg);padding:14px 22px;margin:-22px 0 36px;box-shadow:var(--shadow-sm);flex-wrap:wrap;">
    <div style="display:flex;align-items:center;gap:10px;font-size:0.92rem;font-weight:600;color:var(--navy-950);">
        <span style="font-size:1.3rem;">📲</span>
        <span>Pass reçu instantanément par email</span>
    </div>
    <div style="display:flex;align-items:center;gap:10px;font-size:0.92rem;font-weight:600;color:var(--navy-950);">
        <span style="font-size:1.3rem;">💙</span>
        <span>Payez avec votre compte Wave ou Orange Money</span>
    </div>
    <div style="display:flex;align-items:center;gap:10px;font-size:0.92rem;font-weight:600;color:var(--navy-950);">
        <span style="font-size:1.3rem;">⚡</span>
        <span>Montrez juste votre écran à l'entrée</span>
    </div>
</div>

<!-- Section des Événements -->
<section>
    <div class="section-header">
        <div>
            <h2>
                @if($search)
                    Résultats pour « {{ $search }} »
                @else
                    Les événements du moment
                @endif
            </h2>
            <p class="section-subtitle">
                @if($search)
                    Trouvez ci-dessous les événements correspondants.
                @else
                    Réservez dès maintenant avant que ce ne soit complet !
                @endif
            </p>
        </div>
    </div>

    @if($events->isEmpty())
        <div class="empty-state">
            <div class="empty-icon">
                <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="11" cy="11" r="8"></circle>
                    <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                </svg>
            </div>
            <h3>Aucun événement trouvé</h3>
            <p>Il n'y a pas d'événement pour « <strong>{{ $search }}</strong> ». Essayez une autre ville ou parcourez toute la liste.</p>
            <a class="btn btn-navy" href="{{ route('home') }}">Voir tous les événements</a>
        </div>
    @else
        <div class="grid">
            @foreach($events as $event)
                @php $full = $event->soldCount() >= $event->capacity; @endphp
                <div class="event-card">
                    <a href="{{ route('events.show', $event) }}" style="text-decoration:none;color:inherit;display:block;">
                        <div class="event-media">
                            @if($event->posterUrl())
                                <img src="{{ $event->posterUrl() }}" alt="Affiche : {{ $event->title }}" loading="lazy">
                            @else
                                <div class="event-media-fallback">
                                    <div class="fallback-pattern"></div>
                                    <span class="fallback-letter" aria-hidden="true">{{ mb_strtoupper(mb_substr($event->title, 0, 1)) }}</span>
                                </div>
                            @endif

                            <!-- Badge Date Flottant -->
                            <div class="event-date-badge">
                                <span class="event-date-day">{{ $event->starts_at->format('d') }}</span>
                                <span class="event-date-month">{{ $event->starts_at->translatedFormat('M') }}</span>
                            </div>

                            <!-- Statut -->
                            @if($full)
                                <span class="event-status-pill status-full">Complet</span>
                            @else
                                <span class="event-status-pill status-available">Places dispo</span>
                            @endif
                        </div>
                    </a>

                    <div class="event-content">
                        <div class="event-location">
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
                                <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0Z"/>
                                <circle cx="12" cy="10" r="3"/>
                            </svg>
                            <span>{{ $event->venue }}, {{ $event->city }}</span>
                        </div>

                        <a href="{{ route('events.show', $event) }}" style="text-decoration:none;color:inherit;">
                            <h3 class="event-title">{{ $event->title }}</h3>
                        </a>

                        <div class="event-footer">
                            <div class="event-price-wrap">
                                <span class="price-label">Tarif</span>
                                <span class="price-amount" style="{{ $full ? 'color:var(--red);font-size:1.05rem;' : '' }}">
                                    {{ $full ? 'Guichet fermé' : ($event->price == 0 ? 'Gratuit' : fcfa($event->price)) }}
                                </span>
                            </div>

                            <div style="display:flex;align-items:center;gap:8px;">
                                <!-- Partage WhatsApp avec message prérempli pour les potes -->
                                @php
                                    $shareText = rawurlencode("🔥 Regarde cet événement sur SunuTicket : " . $event->title . " à " . $event->city . " ! Viens on y va : " . route('events.show', $event));
                                @endphp
                                <a class="btn btn-whatsapp btn-sm" href="https://api.whatsapp.com/send?text={{ $shareText }}" target="_blank" title="Envoyer à un ami sur WhatsApp" style="padding:8px 10px;border-radius:var(--radius-sm);" onclick="event.stopPropagation();">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                                        <path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.582 2.128 2.182-.573c.978.58 1.911.928 3.145.929 3.178 0 5.767-2.587 5.768-5.766 0-3.18-2.588-5.771-5.764-5.771zm3.392 8.244c-.144.405-.837.774-1.17.824-.299.045-.677.063-1.092-.069-.252-.08-.575-.187-.988-.365-1.739-.751-2.874-2.502-2.961-2.617-.087-.116-.708-.94-.708-1.793s.448-1.273.607-1.446c.159-.173.346-.217.462-.217l.332.006c.106.005.249-.04.39.298.144.347.491 1.2.534 1.287.043.087.072.188.014.304-.058.116-.087.188-.173.289l-.26.304c-.087.086-.177.18-.076.354.101.174.449.741.964 1.201.662.591 1.221.774 1.394.86.173.086.275.072.376-.044.101-.116.433-.506.549-.68.116-.173.231-.145.39-.087s1.011.477 1.184.564.289.13.332.202c.043.073.043.419-.101.824z"/>
                                    </svg>
                                </a>

                                <a class="btn btn-primary btn-sm" href="{{ route('events.show', $event) }}">
                                    <span>{{ $full ? 'Détails' : 'Prendre mon pass' }}</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="pagination-wrap">
            {{ $events->links('pagination.simple') }}
        </div>
    @endif
</section>

<!-- Appel chaleureux aux organisateurs d'événements -->
<section class="organizer-banner" style="margin-top: 50px;">
    <div class="organizer-banner-content">
        <h3 style="font-size: 1.55rem; color: var(--navy-950); margin-bottom: 8px;">
            Tu organises une soirée, un concert ou une conférence ?
        </h3>
        <p style="color: var(--text-muted); font-size: 1rem; line-height: 1.6;">
            Mets ta billetterie en ligne en 2 minutes. Tes participants payent directement avec Wave ou Orange Money, et tu scannes les entrées à la porte avec ton smartphone. Simple et efficace.
        </p>
    </div>
    <div>
        <a class="btn btn-navy btn-lg" href="{{ route('register') }}">
            <span>Créer ma billetterie gratuitement</span>
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
                <line x1="5" y1="12" x2="19" y2="12"></line>
                <polyline points="12 5 19 12 12 19"></polyline>
            </svg>
        </a>
    </div>
</section>

@endsection