@extends('layouts.app')

@section('title', 'Vos pass · '.$order->event->title)

@section('content')

<div class="ticket-wrapper">
    <!-- En-tête convivial et chaleureux -->
    <div style="background:#ffffff;border:1px solid var(--border);border-radius:var(--radius-xl);padding:32px 28px;box-shadow:var(--shadow-sm);margin-bottom:24px;text-align:center;">
        <div style="width:60px;height:60px;border-radius:50%;background:#dcfce7;color:#15803d;display:grid;place-items:center;margin:0 auto 16px;box-shadow:0 0 0 8px #f0fdf4;">
            <svg width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                <polyline points="20 6 9 17 4 12"></polyline>
            </svg>
        </div>

        <h1 style="font-size:1.85rem;color:var(--navy-950);margin-bottom:8px;">
            C'est validé, vos pass sont prêts !
        </h1>
        <p style="color:var(--text-muted);font-size:1.05rem;max-width:560px;margin:0 auto 20px;">
            Merci <strong>{{ $order->buyer_name }}</strong>. Vos billets électroniques sont disponibles ci-dessous et une copie a été envoyée à <strong>{{ $order->buyer_email }}</strong>.
        </p>

        <!-- Conseil capture d'écran / offline très pratique en soirée -->
        <div class="screenshot-tip" style="max-width:580px;margin:0 auto 24px;text-align:left;">
            <span style="font-size:1.4rem;">💡</span>
            <span>
                <strong>Astuce :</strong> Prenez une <strong>capture d'écran</strong> de votre code QR dès maintenant pour l'avoir sous la main le jour J, même si le réseau est faible à l'entrée.
            </span>
        </div>

        <div style="display:flex;align-items:center;justify-content:center;gap:12px;flex-wrap:wrap;">
            <a class="btn btn-ghost" href="https://calendar.google.com/calendar/render?action=TEMPLATE&text={{ rawurlencode($order->event->title) }}&dates={{ $order->event->starts_at->utc()->format('Ymd\THis\Z') }}/{{ $order->event->starts_at->copy()->addHour()->utc()->format('Ymd\THis\Z') }}&details={{ rawurlencode('Billet SunuTicket — '.$order->event->title.' — '.$order->buyer_name) }}&location={{ rawurlencode($order->event->venue.', '.$order->event->city) }}" target="_blank">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <rect x="3" y="4" width="18" height="18" rx="2"></rect>
                    <line x1="16" y1="2" x2="16" y2="6"></line>
                    <line x1="8" y1="2" x2="8" y2="6"></line>
                    <line x1="3" y1="10" x2="21" y2="10"></line>
                </svg>
                <span>Ajouter au calendrier</span>
            </a>
            <a class="btn btn-primary" href="{{ route('tickets.pdf', $order) }}">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                    <polyline points="7 10 12 15 17 10"></polyline>
                    <line x1="12" y1="15" x2="12" y2="3"></line>
                </svg>
                <span>Télécharger en PDF</span>
            </a>

            @php
                $shareTicketText = rawurlencode("🎟️ Voici le lien vers notre billet pour " . $order->event->title . " : " . url()->current());
            @endphp
            <a class="btn btn-whatsapp" href="{{ route('tickets.calendar', $order) }}" target="_blank">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M7 2h2v2h6V2h2v2h3a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h3V2zm13 9H4v9h16v-9zm-2 2v5H6v-5h12z"/>
                </svg>
                <span>Fichier .ics</span>
            </a>
            <a class="btn btn-whatsapp" href="https://api.whatsapp.com/send?text={{ $shareTicketText }}" target="_blank">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.582 2.128 2.182-.573c.978.58 1.911.928 3.145.929 3.178 0 5.767-2.587 5.768-5.766 0-3.18-2.588-5.771-5.764-5.771zm3.392 8.244c-.144.405-.837.774-1.17.824-.299.045-.677.063-1.092-.069-.252-.08-.575-.187-.988-.365-1.739-.751-2.874-2.502-2.961-2.617-.087-.116-.708-.94-.708-1.793s.448-1.273.607-1.446c.159-.173.346-.217.462-.217l.332.006c.106.005.249-.04.39.298.144.347.491 1.2.534 1.287.043.087.072.188.014.304-.058.116-.087.188-.173.289l-.26.304c-.087.086-.177.18-.076.354.101.174.449.741.964 1.201.662.591 1.221.774 1.394.86.173.086.275.072.376-.044.101-.116.433-.506.549-.68.116-.173.231-.145.39-.087s1.011.477 1.184.564.289.13.332.202c.043.073.043.419-.101.824z"/>
                </svg>
                <span>Envoyer à un ami sur WhatsApp</span>
            </a>

            <button class="btn btn-ghost" onclick="window.print()">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <polyline points="6 9 6 2 18 2 18 9"></polyline>
                    <path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path>
                    <rect x="6" y="14" width="12" height="8"></rect>
                </svg>
                <span>Imprimer</span>
            </button>
        </div>
    </div>

    <!-- Pass Numériques Individuels -->
    @foreach($order->tickets as $ticket)
        <div class="ticket-card {{ $ticket->status === 'used' ? 'is-used' : '' }}">
            <div class="ticket-left">
                <div class="ticket-header-band">
                    <div class="ticket-brand-mark">
                        <span style="display:inline-block;width:10px;height:10px;border-radius:50%;background:var(--teal-500);"></span>
                        <span>SunuTicket Pass Officiel</span>
                    </div>
                    <span class="ticket-number-badge">
                        Billet {{ $loop->iteration }} sur {{ $order->tickets->count() }}
                    </span>
                </div>

                <h2 class="ticket-event-title">{{ $order->event->title }}</h2>

                <dl class="ticket-info-grid">
                    <div class="ticket-info-item">
                        <dt>Date & Heure</dt>
                        <dd>{{ $order->event->starts_at->translatedFormat('d F Y \à H\hi') }}</dd>
                    </div>
                    <div class="ticket-info-item">
                        <dt>Lieu / Salle</dt>
                        <dd>{{ $order->event->venue }}, {{ $order->event->city }}</dd>
                    </div>
                    <div class="ticket-info-item">
                        <dt>Titulaire</dt>
                        <dd style="color:var(--teal-800);">{{ $ticket->holder_name }}</dd>
                    </div>
                    <div class="ticket-info-item">
                        <dt>Code Court</dt>
                        <dd style="font-family:monospace;font-size:0.95rem;letter-spacing:0.08em;">{{ $ticket->shortCode() }}</dd>
                    </div>
                </dl>

                <p style="font-size:0.82rem;color:var(--text-muted);margin:0;line-height:1.4;">
                    ℹ️ Présentez simplement ce code QR sur votre smartphone aux agents d'accueil à l'entrée.
                </p>
            </div>

            <!-- Volet QR code découpé -->
            <div class="ticket-right">
                <span class="ticket-notch-top" aria-hidden="true"></span>
                <span class="ticket-notch-bottom" aria-hidden="true"></span>

                <div class="ticket-qr-container">
                    {!! \App\Services\TicketQr::inline($ticket, 220) !!}
                </div>

                <div class="ticket-code-display">{{ $ticket->shortCode() }}</div>

                @if($ticket->status === 'used')
                    <span class="ticket-status-stamp status-used">Entrée validée</span>
                @else
                    <span class="ticket-status-stamp status-valid">Billet Valide</span>
                @endif
            </div>
        </div>
    @endforeach

    <div style="text-align:center;margin-top:24px;">
        <a class="btn btn-ghost" href="{{ route('home') }}">← Retour aux événements</a>
    </div>
</div>

@endsection
