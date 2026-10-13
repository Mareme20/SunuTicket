@extends('layouts.app')

@section('title', 'Statut du paiement · SunuTicket')

@section('content')
@if($order->status === 'pending')
    @push('head')<meta http-equiv="refresh" content="10">@endpush
@endif

<section class="panel narrow" style="text-align:center;padding:48px 32px;">
    @if($order->status === 'pending')
        <div style="width:64px;height:64px;border-radius:50%;background:var(--gold-50);color:var(--gold-600);display:grid;place-items:center;margin:0 auto 20px;border:2px solid var(--gold-400);">
            <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" style="animation:spin 2s linear infinite;">
                <circle cx="12" cy="12" r="10"></circle>
                <path d="M12 6v6l4 2"></path>
            </svg>
        </div>

        <h1 style="font-size:1.75rem;color:var(--navy-950);margin-bottom:12px;">Paiement en cours de vérification</h1>
        <p style="color:var(--text-muted);font-size:1rem;line-height:1.6;margin-bottom:20px;">
            Nous attendons la confirmation de l'opérateur (Wave / Orange Money) pour « <strong>{{ $order->event->title }}</strong> ».
        </p>
        <p style="color:var(--text-muted);font-size:0.92rem;margin-bottom:14px;">
            Vos places sont réservées jusqu'à <strong>{{ $order->expiresAt()->translatedFormat('H\hi') }}</strong>.
        </p>
        <p style="background:var(--bg-surface);border:1px solid var(--border);border-radius:var(--radius-md);padding:12px;font-size:0.88rem;color:var(--text-main);margin-bottom:24px;">
            💡 Dès validation, vos billets seront immédiatement envoyés par email à <strong>{{ $order->buyer_email }}</strong> et s'afficheront sur cette page.
        </p>

        <a class="btn btn-primary btn-block" href="{{ route('checkout.return', $order) }}">
            <span>Vérifier manuellement</span>
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <polyline points="23 4 23 10 17 10"></polyline>
                <path d="M20.49 15a9 9 0 1 1-2.12-9.36L23 10"></path>
            </svg>
        </a>
    @elseif($order->status === 'expired')
        <div style="width:64px;height:64px;border-radius:50%;background:var(--gold-50);color:var(--gold-600);display:grid;place-items:center;margin:0 auto 20px;border:2px solid var(--gold-400);">
            <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                <circle cx="12" cy="12" r="10"></circle>
                <polyline points="12 6 12 12 16 14"></polyline>
            </svg>
        </div>

        <h1 style="font-size:1.75rem;color:var(--navy-950);margin-bottom:12px;">Réservation expirée</h1>
        <p style="color:var(--text-muted);font-size:1rem;line-height:1.6;margin-bottom:24px;">
            Le paiement n’a pas été confirmé à temps. Les places ont été libérées pour les autres acheteurs.
        </p>

        <a class="btn btn-navy btn-block" href="{{ route('events.show', $order->event) }}">
            <span>Recommencer l'achat</span>
        </a>
    @else
        <div style="width:64px;height:64px;border-radius:50%;background:var(--red-bg);color:var(--red);display:grid;place-items:center;margin:0 auto 20px;border:2px solid #fca5a5;">
            <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                <circle cx="12" cy="12" r="10"></circle>
                <line x1="15" y1="9" x2="9" y2="15"></line>
                <line x1="9" y1="9" x2="15" y2="15"></line>
            </svg>
        </div>

        <h1 style="font-size:1.75rem;color:var(--navy-950);margin-bottom:12px;">Le paiement n’a pas abouti</h1>
        <p style="color:var(--text-muted);font-size:1rem;line-height:1.6;margin-bottom:24px;">
            La transaction a été annulée ou a rencontré un problème. Aucun montant n'a été débité de votre compte.
        </p>

        <a class="btn btn-navy btn-block" href="{{ route('events.show', $order->event) }}">
            <span>Réessayer l'achat de billets</span>
        </a>
    @endif
</section>

@push('head')
<style>
@keyframes spin {
    from { transform: rotate(0deg); }
    to { transform: rotate(360deg); }
}
</style>
@endpush

@endsection
