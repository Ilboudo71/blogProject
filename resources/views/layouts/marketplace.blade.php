<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'MarketPlace') — MarketPlace</title>
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
                <span class="brand-mark">M</span>
                <span class="brand-text">MarketPlace</span>
            </a>

            <nav class="header-nav" aria-label="Navigation principale">
                <a href="{{ route('marketplace.home') }}#catalogue" class="nav-link">Catalogue</a>
                @auth
                    <a href="{{ auth()->user()->panelHomeUrl() }}" class="btn btn-primary">Mon espace</a>
                @else
                    <a href="/user/login" class="btn btn-ghost">Connexion</a>
                    <a href="/user/register" class="btn btn-primary">Créer un compte</a>
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
                        <span class="brand-mark">M</span>
                        <span class="brand-text">MarketPlace</span>
                    </a>
                    <p class="footer-copy">
                        Plateforme pour exposer, publier et contacter les vendeurs en toute simplicité.
                    </p>
                </div>

                <div>
                    <h3 class="footer-title">Navigation</h3>
                    <ul class="footer-list">
                        <li><a href="{{ route('marketplace.home') }}">Accueil</a></li>
                        <li><a href="{{ route('marketplace.home') }}#catalogue">Catalogue</a></li>
                        <li><a href="/user/register">Devenir vendeur</a></li>
                        <li><a href="/user/login">Connexion</a></li>
                    </ul>
                </div>

                <div>
                    <h3 class="footer-title">Informations</h3>
                    <ul class="footer-list">
                        <li><a href="{{ route('marketplace.about') }}">À propos</a></li>
                        <li><a href="{{ route('marketplace.privacy') }}">Politique de confidentialité</a></li>
                        <li><a href="{{ route('marketplace.terms') }}">Conditions d’utilisation</a></li>
                    </ul>
                </div>

                <div>
                    <h3 class="footer-title">Contact</h3>
                    <ul class="footer-list footer-contact">
                        <li>
                            <span>WhatsApp</span>
                            <a href="https://wa.me/22674650924" target="_blank" rel="noopener noreferrer">+226 74 65 09 24</a>
                        </li>
                        <li>
                            <span>E-mail</span>
                            <a href="mailto:ilboudo7199@gmail.com">ilboudo7199@gmail.com</a>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="footer-bottom">
                <p>&copy; {{ date('Y') }} MarketPlace. Tous droits réservés.</p>
                <p>Conçu pour une mise en relation simple entre vendeurs et acheteurs.</p>
            </div>
        </div>
    </footer>
</body>
</html>
