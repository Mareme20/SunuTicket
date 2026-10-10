@extends('layouts.app')

@section('title', 'Connexion Organisateur')

@section('content')
<section class="panel narrow">
    <div class="panel-header">
        <div class="panel-logo">
            <img src="{{ asset('images/logo-sunuticket.png') }}" alt="Logo SunuTicket" style="width:100%;height:100%;object-fit:contain;">
        </div>
        <h1>Connexion organisateur</h1>
        <p>Gérez vos événements, suivez vos recettes et validez les entrées.</p>
    </div>

    <form method="post" action="{{ route('login') }}">
        @csrf

        <div class="form-group">
            <label for="phone">Numéro de téléphone</label>
            <input id="phone" class="form-control" name="phone" type="tel" inputmode="tel" autocomplete="tel" value="{{ old('phone') }}" placeholder="Ex : 77 123 45 67" required autofocus>
            @error('phone')<p class="err">{{ $message }}</p>@enderror
        </div>

        <div class="form-group">
            <label for="password">Mot de passe</label>
            <input id="password" class="form-control" name="password" type="password" autocomplete="current-password" placeholder="••••••••" required>
            @error('password')<p class="err">{{ $message }}</p>@enderror
        </div>

        <label class="checkbox-label">
            <input type="checkbox" name="remember" value="1">
            <span>Se souvenir de moi</span>
        </label>

        <button class="btn btn-primary btn-block btn-lg" style="margin-top:10px;">
            <span>Se connecter</span>
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
                <line x1="5" y1="12" x2="19" y2="12"></line>
                <polyline points="12 5 19 12 12 19"></polyline>
            </svg>
        </button>
    </form>

    <p class="hint">
        Pas encore inscrit ? <a href="{{ route('register') }}" style="font-weight:700;color:var(--teal-600);">Créer un compte en 1 minute</a>
    </p>
</section>
@endsection
