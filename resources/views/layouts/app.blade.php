<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Vos sorties & billets au Sénégal') · SunuTicket</title>

    <!-- Google Fonts : Plus Jakarta Sans & Bricolage Grotesque -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:opsz,wght@12..96,600;12..96,700;12..96,800&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    @stack('head')
</head>
<body>

<header class="top">
    <div class="top-in">
        <a class="brand" href="{{ route('home') }}" aria-label="Accueil SunuTicket">
            <div class="brand-logo-wrap">
                <img src="{{ asset('images/logo-sunuticket.png') }}" alt="Logo SunuTicket">
            </div>
            <div class="brand-text">
                <span class="brand-name">
                    SunuTicket
                    <span class="accent-dot" aria-hidden="true"></span>
                </span>
                <span class="brand-tagline">Billetterie Sénégal</span>
            </div>
        </a>

        <nav class="nav" aria-label="Navigation principale">
            <a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                    <line x1="16" y1="2" x2="16" y2="6"></line>
                    <line x1="8" y1="2" x2="8" y2="6"></line>
                    <line x1="3" y1="10" x2="21" y2="10"></line>
                </svg>
                <span>Les événements</span>
            </a>

            @auth
                @if(auth()->user()->isAdmin())
                    <a class="nav-link {{ request()->routeIs('admin.*') ? 'active' : '' }}" href="{{ route('admin.index') }}">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
                        </svg>
                        <span>Administration</span>
                    </a>
                @endif

                <a class="nav-link {{ request()->routeIs('organizer.*') ? 'active' : '' }}" href="{{ route('organizer.dashboard') }}">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="18" y1="20" x2="18" y2="10"></line>
                        <line x1="12" y1="20" x2="12" y2="4"></line>
                        <line x1="6" y1="20" x2="6" y2="14"></line>
                    </svg>
                    <span>Mes événements</span>
                </a>

                <span class="nav-divider"></span>

                <div class="user-badge" title="Connecté : {{ auth()->user()->name }}">
                    <span class="user-avatar">{{ mb_strtoupper(mb_substr(auth()->user()->name, 0, 1)) }}</span>
                    <span>{{ Str::limit(auth()->user()->name, 14) }}</span>
                </div>

                <form method="post" action="{{ route('logout') }}" style="margin:0;">
                    @csrf
                    <button class="linkish" title="Se déconnecter">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                            <polyline points="16 17 21 12 16 7"></polyline>
                            <line x1="21" y1="12" x2="9" y2="12"></line>
                        </svg>
                    </button>
                </form>
            @else
                <a class="nav-link {{ request()->routeIs('login') ? 'active' : '' }}" href="{{ route('login') }}">
                    <span>Connexion</span>
                </a>

                <a class="btn btn-primary btn-sm" href="{{ route('register') }}">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="12" y1="5" x2="12" y2="19"></line>
                        <line x1="5" y1="12" x2="19" y2="12"></line>
                    </svg>
                    <span>Créer un événement</span>
                </a>
            @endauth
        </nav>
    </div>
</header>

<main class="wrap">
    @if(session('status'))
        <div class="flash ok" role="status">
            <svg class="flash-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                <polyline points="22 4 12 14.01 9 11.01"></polyline>
            </svg>
            <div>{{ session('status') }}</div>
        </div>
    @endif

    @if($errors->has('payment'))
        <div class="flash bad" role="alert">
            <svg class="flash-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <circle cx="12" cy="12" r="10"></circle>
                <line x1="12" y1="8" x2="12" y2="12"></line>
                <line x1="12" y1="16" x2="12.01" y2="16"></line>
            </svg>
            <div>{{ $errors->first('payment') }}</div>
        </div>
    @endif

    @yield('content')
</main>

<footer class="foot">
    <div class="foot-top">
        <div class="foot-brand">
            <h4>
                <span style="display:inline-block;width:10px;height:10px;border-radius:50%;background:var(--teal-400);"></span>
                SunuTicket Sénégal
            </h4>
            <p>La billetterie digitale simple et conviviale pour vivre la culture, les concerts et les soirées au Sénégal. Fini la queue au guichet !</p>
            <div class="payment-badges-row">
                <span class="badge-pay" style="background:#00a4e4;border-color:#00a4e4;">Wave</span>
                <span class="badge-pay" style="background:#ff6600;border-color:#ff6600;">Orange Money</span>
                <span class="badge-pay">PayDunya</span>
            </div>
        </div>

        <div class="foot-col">
            <h5>Accès direct</h5>
            <ul class="foot-links">
                <li><a href="{{ route('home') }}">Tous les événements</a></li>
                <li><a href="{{ route('tickets.lookup') }}">Retrouver mes billets</a></li>
                @auth
                    <li><a href="{{ route('organizer.dashboard') }}">Mon espace organisateur</a></li>
                    <li><a href="{{ route('organizer.events.create') }}">Publier un événement</a></li>
                @else
                    <li><a href="{{ route('login') }}">Connexion organisateur</a></li>
                    <li><a href="{{ route('register') }}">Lancer ma billetterie</a></li>
                @endauth
            </ul>
        </div>

        <div class="foot-col">
            <h5>Besoin d'aide ?</h5>
            <p style="font-size:0.88rem;color:rgba(255,255,255,0.7);line-height:1.6;margin-bottom:12px;">
                Une question sur un billet ou un paiement ? Contactez notre support local disponible 7j/7.
            </p>
            <a class="btn btn-whatsapp btn-sm" href="https://api.whatsapp.com/send?phone=221770000000&text=Bonjour%20SunuTicket%2C%20j%27ai%20une%20question%20concernant%20un%20billet" target="_blank">
                <span>Contacter le support WhatsApp</span>
            </a>
        </div>
    </div>

    <div class="foot-bottom">
        &copy; {{ date('Y') }} SunuTicket. Fait avec passion à Dakar pour le Sénégal.
    </div>
</footer>

@stack('scripts')
</body>
</html>