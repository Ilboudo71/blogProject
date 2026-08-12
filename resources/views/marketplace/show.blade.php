@extends('layouts.marketplace')

@section('title', $product->name)

@php
    $seller = $product->user;
    $mailto = $seller?->mailtoInquiryUrl($product->name);
    $whatsapp = $seller?->whatsappInquiryUrl($product->name);
@endphp

@section('content')
<section class="product-detail">
    <div class="container detail-grid">
        <div class="detail-media">
            <div class="detail-media-frame">
                @if ($product->photo_url)
                    <img src="{{ $product->photo_url }}" alt="{{ $product->name }}">
                @else
                    <div class="product-fallback large">{{ strtoupper(substr($product->name, 0, 1)) }}</div>
                @endif
            </div>
        </div>

        <div class="detail-copy">
            <p class="product-category">{{ $product->type_label }}</p>
            <h1>{{ $product->name }}</h1>
            <p class="detail-price">{{ number_format((float) $product->price, 0, ',', ' ') }} FCFA</p>

            @if (filled($product->description))
                <section class="detail-description-block" aria-labelledby="product-description-title">
                    <h2 id="product-description-title" class="detail-section-title">Description</h2>
                    <p class="detail-description">{{ $product->description }}</p>
                </section>
            @endif

            <dl class="detail-facts">
                <div>
                    <dt>Catégorie</dt>
                    <dd>{{ $product->type_label }}</dd>
                </div>
                <div>
                    <dt>Vues</dt>
                    <dd>{{ number_format($product->views_count, 0, ',', ' ') }}</dd>
                </div>
                <div>
                    <dt>Likes</dt>
                    <dd>
                        @include('marketplace.partials.like-button', [
                            'product' => $product,
                            'isLiked' => $isLiked ?? false,
                        ])
                    </dd>
                </div>
            </dl>

            @if ($seller)
                <aside class="seller-card">
                    <div class="seller-card-head">
                        <div class="seller-avatar" aria-hidden="true">
                            @if ($seller->photo_url)
                                <img src="{{ $seller->photo_url }}" alt="">
                            @else
                                {{ strtoupper(substr($seller->full_name ?: $seller->name, 0, 1)) }}
                            @endif
                        </div>
                        <div>
                            <p class="seller-label">Vendeur</p>
                            <h2 class="seller-name">{{ $seller->full_name ?: $seller->name }}</h2>
                            @if ($seller->locality)
                                <p class="seller-locality">{{ $seller->locality }}</p>
                            @endif
                        </div>
                    </div>

                    <div class="seller-contact-rows">
                        @if ($seller->formatted_phone)
                            <div class="seller-contact-row">
                                <div>
                                    <span class="seller-contact-label">Numéro</span>
                                    <a class="seller-contact-value" href="tel:{{ preg_replace('/\s+/', '', $seller->formatted_phone) }}">{{ $seller->formatted_phone }}</a>
                                </div>
                                @if ($whatsapp)
                                    <a href="{{ $whatsapp }}" target="_blank" rel="noopener noreferrer" class="btn btn-whatsapp">
                                        WhatsApp
                                    </a>
                                @endif
                            </div>
                        @endif

                        @if ($seller->email)
                            <div class="seller-contact-row">
                                <div>
                                    <span class="seller-contact-label">E-mail</span>
                                    <a class="seller-contact-value" href="mailto:{{ $seller->email }}">{{ $seller->email }}</a>
                                </div>
                                @if ($mailto)
                                    <a href="{{ $mailto }}" class="btn btn-mail">
                                        Laisser un message
                                    </a>
                                @endif
                            </div>
                        @endif
                    </div>

                    @if ($mailto || $whatsapp)
                        <div class="seller-cta-row">
                            @if ($mailto)
                                <a href="{{ $mailto }}" class="btn btn-mail btn-lg">Écrire par e-mail</a>
                            @endif
                            @if ($whatsapp)
                                <a href="{{ $whatsapp }}" target="_blank" rel="noopener noreferrer" class="btn btn-whatsapp btn-lg">Écrire sur WhatsApp</a>
                            @endif
                        </div>
                    @endif
                </aside>
            @endif

            <div class="hero-actions">
                <a href="{{ route('marketplace.home') }}#catalogue" class="btn btn-ghost">Retour au catalogue</a>
            </div>
        </div>
    </div>
</section>

@if ($related->isNotEmpty())
<section class="catalogue related">
    <div class="container">
        <div class="section-head">
            <div>
                <h2>Produits similaires</h2>
                <p>Autres annonces de la même catégorie.</p>
            </div>
        </div>
        <div class="product-grid">
            @foreach ($related as $item)
                @include('marketplace.partials.product-card', ['product' => $item])
            @endforeach
        </div>
    </div>
</section>
@endif
@endsection
