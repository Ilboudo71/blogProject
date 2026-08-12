@extends('layouts.marketplace')

@section('title', 'Politique de confidentialité')

@section('content')
<section class="legal-page">
    <div class="container legal-wrap">
        <p class="eyebrow">MarketPlace</p>
        <h1>Politique de confidentialité</h1>
        <p class="legal-lead">
            Cette politique explique quelles informations sont collectées sur MarketPlace et comment elles sont utilisées.
            Dernière mise à jour : {{ now()->format('d/m/Y') }}.
        </p>

        <div class="legal-blocks">
            <article>
                <h2>1. Données collectées</h2>
                <p>
                    Nous pouvons collecter votre nom, prénom, adresse e-mail, numéro de téléphone, localité,
                    ainsi que les informations liées à vos produits publiés et à votre connexion.
                </p>
            </article>
            <article>
                <h2>2. Utilisation des données</h2>
                <p>
                    Ces données servent à créer et gérer votre compte, exposer vos produits, permettre le contact
                    entre acheteurs et vendeurs, et améliorer le service.
                </p>
            </article>
            <article>
                <h2>3. Partage des informations</h2>
                <p>
                    Les coordonnées des vendeurs (téléphone, e-mail, localité) sont visibles publiquement
                    sur les produits publiés afin de faciliter le contact. Elles ne sont pas vendues à des tiers.
                </p>
            </article>
            <article>
                <h2>4. Sécurité</h2>
                <p>
                    Nous mettons en œuvre des mesures raisonnables pour protéger vos informations.
                    Vous êtes responsable de la confidentialité de votre mot de passe.
                </p>
            </article>
            <article>
                <h2>5. Vos droits</h2>
                <p>
                    Vous pouvez demander la mise à jour ou la suppression de vos données en nous contactant
                    à l’adresse e-mail ci-dessous.
                </p>
            </article>
            <article>
                <h2>6. Contact</h2>
                <p>
                    Pour toute question relative à la confidentialité :
                    <a href="mailto:ilboudo7199@gmail.com">ilboudo7199@gmail.com</a>
                    ou WhatsApp
                    <a href="https://wa.me/22674650924" target="_blank" rel="noopener noreferrer">+226 74 65 09 24</a>.
                </p>
            </article>
        </div>
    </div>
</section>
@endsection
