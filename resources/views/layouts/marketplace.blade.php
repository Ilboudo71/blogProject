<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Raaga') — Raaga</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="preconnect" href="https://images.unsplash.com">
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,500;9..144,700&family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="marketplace-body">
    <div class="marketplace-noise" aria-hidden="true"></div>

    <header class="site-header @yield('header_class')">
        <div class="container header-inner">
            <a href="{{ route('marketplace.home') }}" class="brand">
                <span class="brand-mark">R</span>
                <span class="brand-text">Raaga</span>
            </a>

            <nav class="header-nav" aria-label="Navigation principale">
                <a href="{{ route('marketplace.home') }}#catalogue" class="nav-link">{{ __('Catalogue') }}</a>
                @auth
                    <a href="{{ auth()->user()->panelHomeUrl() }}" class="btn btn-primary">{{ __('Mon espace') }}</a>
                @else
                    <a href="/user/login" class="btn btn-ghost">{{ __('Connexion') }}</a>
                    <a href="/user/register" class="btn btn-primary">{{ __('Créer un compte') }}</a>
                @endauth
            </nav>
        </div>
    </header>

    <main>
        @yield('content')
    </main>

    <footer class="site-footer site-footer--pro">
        <div class="container">
            <div class="footer-grid">
                <div class="footer-brand-col">
                    <a href="{{ route('marketplace.home') }}" class="brand footer-brand">
                        <span class="brand-mark">R</span>
                        <span class="brand-text">Raaga</span>
                    </a>
                    <p class="footer-copy">
                        {{ __('Plateforme locale pour exposer, publier et contacter les vendeurs en toute simplicité.') }}
                    </p>
                </div>

                <div>
                    <h3 class="footer-title">{{ __('Navigation') }}</h3>
                    <ul class="footer-list">
                        <li><a href="{{ route('marketplace.home') }}">{{ __('Accueil') }}</a></li>
                        <li><a href="{{ route('marketplace.home') }}#catalogue">{{ __('Catalogue') }}</a></li>
                        <li><a href="/user/register">{{ __('Devenir vendeur') }}</a></li>
                        <li><a href="/user/login">{{ __('Connexion') }}</a></li>
                    </ul>
                </div>

                <div>
                    <h3 class="footer-title">{{ __('Informations') }}</h3>
                    <ul class="footer-list">
                        <li><a href="{{ route('marketplace.about') }}">{{ __('À propos') }}</a></li>
                        <li><a href="{{ route('marketplace.privacy') }}">{{ __('Politique de confidentialité') }}</a></li>
                        <li><a href="{{ route('marketplace.terms') }}">{{ __('Conditions d’utilisation') }}</a></li>
                    </ul>
                </div>

                <div>
                    <h3 class="footer-title">{{ __('Contact') }}</h3>
                    <ul class="footer-list footer-contact">
                        <li>
                            <span>WhatsApp</span>
                            <a href="https://wa.me/22674650924" target="_blank" rel="noopener noreferrer">+226 74 65 09 24</a>
                        </li>
                        <li>
                            <span>{{ __('E-mail') }}</span>
                            <a href="mailto:ilboudo7199@gmail.com">ilboudo7199@gmail.com</a>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="footer-bottom">
                <p>&copy; {{ date('Y') }} Raaga. {{ __('Tous droits réservés.') }}</p>
                <p>{{ __('Conçu pour une mise en relation simple entre vendeurs et acheteurs.') }}</p>
            </div>
        </div>
    </footer>
</body>
</html>
