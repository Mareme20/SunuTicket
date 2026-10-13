@extends('layouts.app')

@section('title', 'Retrouver mes billets')

@section('content')

<section class="panel" style="max-width:760px;margin:0 auto 40px;">
    <div style="border-bottom:1px solid var(--border);padding-bottom:18px;margin-bottom:24px;">
        <h1 style="font-size:1.9rem;color:var(--navy-950);margin:0;">Retrouver mes billets</h1>
        <p style="color:var(--text-muted);margin:8px 0 0;">Saisissez le numéro de téléphone ou l’email utilisé lors de l’achat.</p>
    </div>

    <form method="post" action="{{ route('tickets.search') }}" class="checkout-form">
        @csrf
        <div class="form-group">
            <label for="lookup">Téléphone ou email</label>
            <input id="lookup" class="form-control" name="lookup" value="{{ old('lookup', $lookupLabel ?? $phone ?? '') }}" type="text" inputmode="email" placeholder="Ex : 77 123 45 67 ou fatou@gmail.com" required>
            @error('lookup')<p class="err">{{ $message }}</p>@enderror
        </div>

        <button class="btn btn-primary btn-lg" type="submit">Chercher mes billets</button>
    </form>

    @if(isset($orders) && $orders !== [])
        <div style="margin-top:28px;">
            <h2 style="font-size:1.2rem;color:var(--navy-950);margin-bottom:12px;">Billets trouvés pour {{ $lookupLabel }}</h2>

            <div class="list-stack">
                @foreach($orders as $order)
                    <div style="border:1px solid var(--border);border-radius:var(--radius-md);padding:16px 18px;background:var(--bg-surface);display:flex;align-items:center;justify-content:space-between;gap:16px;flex-wrap:wrap;">
                        <div>
                            <div style="font-weight:700;color:var(--navy-950);">{{ $order->event->title }}</div>
                            <div style="color:var(--text-muted);font-size:0.9rem; margin-top:4px;">
                                {{ $order->event->starts_at->translatedFormat('d F Y \à H\hi') }} · {{ $order->event->venue }}, {{ $order->event->city }}
                            </div>
                        </div>
                        <div style="display:flex;align-items:center;gap:10px;flex-wrap:wrap;">
                            <span class="badge-pill paid">{{ $order->quantity }} billet{{ $order->quantity > 1 ? 's' : '' }}</span>
                            <a class="btn btn-ghost btn-sm" href="{{ route('tickets.show', $order) }}">Voir mes billets</a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @elseif(isset($orders))
        <div class="empty-state" style="margin-top:24px;">
            <h3>Aucun billet trouvé</h3>
            <p>Nous n’avons pas trouvé de commande validée pour cette recherche. Vérifiez le téléphone ou l’email, puis réessayez.</p>
        </div>
    @endif
</section>

@endsection
