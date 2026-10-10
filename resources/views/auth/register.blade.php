@extends('layouts.app')

@section('title', 'Créer un compte organisateur')

@section('content')
<section class="panel narrow">
    <div class="panel-header">
        <div class="panel-logo">
            <img src="{{ asset('images/logo-sunuticket.png') }}" alt="Logo SunuTicket" style="width:100%;height:100%;object-fit:contain;">
        </div>
        <h1>Créer un compte organisateur</h1>
        <p>Commencez à vendre vos billets en ligne et encaissez via Wave & Orange Money.</p>
    </div>

    <form method="post" action="{{ route('register') }}">
        @csrf

        <div class="form-group">
            <label for="name">Nom complet ou nom de l'organisation</label>
            <input id="name" class="form-control" name="name" value="{{ old('name') }}" placeholder="Ex : Dakar Music Prod" autocomplete="name" required autofocus>
            @error('name')<p class="err">{{ $message }}</p>@enderror
        </div>

        <div class="form-group">
            <label for="phone">Numéro de téléphone (Wave / OM)</label>
            <input id="phone" class="form-control" name="phone" type="tel" inputmode="tel" autocomplete="tel" value="{{ old('phone') }}" placeholder="Ex : 77 123 45 67" required>
            @error('phone')<p class="err">{{ $message }}</p>@enderror
        </div>

        <div class="form-group">
            <label for="email">Adresse email (facultatif)</label>
            <input id="email" class="form-control" name="email" type="email" autocomplete="email" value="{{ old('email') }}" placeholder="contact@organisation.sn">
            @error('email')<p class="err">{{ $message }}</p>@enderror
        </div>

        <div class="two-cols">
            <div class="form-group">
                <label for="password">Mot de passe</label>
                <input id="password" class="form-control" name="password" type="password" autocomplete="new-password" placeholder="8 car. minimum" required>
                @error('password')<p class="err">{{ $message }}</p>@enderror
            </div>

            <div class="form-group">
                <label for="password_confirmation">Confirmer</label>
                <input id="password_confirmation" class="form-control" name="password_confirmation" type="password" autocomplete="new-password" placeholder="Répéter mot de passe" required>
            </div>
        </div>

        <button class="btn btn-primary btn-block btn-lg" style="margin-top:14px;">
            <span>Créer mon compte organisateur</span>
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
                <line x1="5" y1="12" x2="19" y2="12"></line>
                <polyline points="12 5 19 12 12 19"></polyline>
            </svg>
        </button>
    </form>

    <p class="hint">
        Vous avez déjà un compte ? <a href="{{ route('login') }}" style="font-weight:700;color:var(--teal-600);">Se connecter</a>
    </p>
</section>
@endsection
