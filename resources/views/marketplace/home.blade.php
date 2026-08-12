@extends('layouts.marketplace')

@section('title', 'Catalogue')
@section('header_class', 'site-header--transparent')

@section('content')
<section class="hero hero--bleed">
    <div class="hero-media" aria-hidden="true">
        <img
            src="https://images.unsplash.com/photo-1441986300917-64674bd600d8?auto=format&fit=crop&w=2000&q=80"
            alt=""
            class="hero-media-img"
        >
        <div class="hero-media-shade"></div>
    </div>

    <div class="container hero-content">
        <p class="eyebrow eyebrow--light">Marketplace locale</p>
        <h1 class="hero-title hero-title--light">
            <span class="brand-inline brand-inline--light">MarketPlace</span>
            Des produits soigneusement exposés.
        </h1>
        <p class="hero-lead hero-lead--light">
            Parcourez les annonces publiées, contactez les vendeurs par e-mail ou WhatsApp, et créez votre propre espace en quelques minutes.
        </p>
        <div class="hero-actions">
            @auth
                <a href="{{ auth()->user()->panelHomeUrl() }}" class="btn btn-primary btn-lg">Aller à mon espace</a>
            @else
                <a href="/user/register" class="btn btn-primary btn-lg">Créer un compte</a>
                <a href="#catalogue" class="btn btn-glass btn-lg">Voir le catalogue</a>
            @endauth
        </div>
    </div>
</section>

<section id="catalogue" class="catalogue">
    <div class="container">
        <div class="section-head">
            <div>
                <h2>Produits exposés</h2>
                <p>Découvrez les annonces et écrivez directement aux vendeurs.</p>
            </div>
        </div>

        <form method="GET" action="{{ route('marketplace.home') }}" class="filters">
            <div class="search-field">
                <label for="q" class="sr-only">Rechercher</label>
                <input id="q" type="search" name="q" value="{{ $search }}" placeholder="Rechercher un produit…">
            </div>
            <div class="type-chips" role="group" aria-label="Catégories">
                <a href="{{ route('marketplace.home', array_filter(['q' => $search ?: null])) }}" class="chip {{ $activeType === '' ? 'is-active' : '' }}">Tous</a>
                @foreach ($types as $key => $label)
                    <a href="{{ route('marketplace.home', array_filter(['type' => $key, 'q' => $search ?: null])) }}" class="chip {{ $activeType === $key ? 'is-active' : '' }}">{{ $label }}</a>
                @endforeach
            </div>
            <button type="submit" class="btn btn-primary">Filtrer</button>
        </form>

        @if ($products->isEmpty())
            <div class="empty-state">
                <h3>Aucun produit publié pour le moment</h3>
                <p>Créez un compte vendeur pour exposer votre premier produit.</p>
                <a href="/user/register" class="btn btn-primary">Créer un compte</a>
            </div>
        @else
            <div class="product-grid">
                @foreach ($products as $product)
                    @include('marketplace.partials.product-card', ['product' => $product])
                @endforeach
            </div>

            <div class="pagination-wrap">
                {{ $products->links() }}
            </div>
        @endif
    </div>
</section>

<section class="feature-strip">
    <div class="container feature-strip-grid">
        <article class="feature-card">
            <img src="https://images.unsplash.com/photo-1556740738-b6a63e27c4df?auto=format&fit=crop&w=900&q=80" alt="Vendeur présentant ses produits" loading="lazy">
            <div>
                <h3>Exposez facilement</h3>
                <p>Ajoutez vos produits, publiez-les et suivez leur visibilité.</p>
            </div>
        </article>
        <article class="feature-card">
            <img src="https://images.unsplash.com/photo-1516321318423-f06f85e504b3?auto=format&fit=crop&w=900&q=80" alt="Échange avec un client" loading="lazy">
            <div>
                <h3>Contactez directement</h3>
                <p>Les acheteurs vous joignent par e-mail ou WhatsApp en un clic.</p>
            </div>
        </article>
    </div>
</section>

@guest
<section class="cta-band">
    <div class="container cta-band-inner">
        <div>
            <h2>Prêt à exposer vos produits ?</h2>
            <p>Inscrivez-vous gratuitement : votre compte est créé en tant que vendeur.</p>
        </div>
        <div class="hero-actions">
            <a href="/user/register" class="btn btn-primary btn-lg">Créer mon compte</a>
            <a href="/user/login" class="btn btn-ghost btn-lg">Se connecter</a>
        </div>
    </div>
</section>
@endguest
@endsection
