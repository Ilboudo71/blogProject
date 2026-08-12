@extends('layouts.marketplace')

@section('title', 'À propos')

@section('content')
<section class="legal-page">
    <div class="container legal-wrap">
        <p class="eyebrow">MarketPlace</p>
        <h1>À propos</h1>
        <p class="legal-lead">
            MarketPlace est une plateforme locale qui permet aux vendeurs d’exposer, publier et suivre leurs produits,
            et aux acheteurs de découvrir des annonces puis de contacter directement les vendeurs.
        </p>

        <div class="legal-grid">
            <article>
                <h2>Notre mission</h2>
                <p>
                    Faciliter la mise en relation entre vendeurs et acheteurs grâce à une vitrine simple,
                    claire et accessible, avec contact e-mail et WhatsApp en un clic.
                </p>
            </article>
            <article>
                <h2>Pour les vendeurs</h2>
                <p>
                    Créez votre compte, ajoutez vos produits, publiez-les quand vous voulez et suivez leur visibilité
                    depuis votre espace personnel.
                </p>
            </article>
            <article>
                <h2>Pour le public</h2>
                <p>
                    Parcourez le catalogue, filtrez par catégorie et contactez le vendeur directement
                    pour toute information ou négociation.
                </p>
            </article>
        </div>

        <div class="legal-contact-box">
            <h2>Nous contacter</h2>
            <ul>
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
</section>
@endsection
