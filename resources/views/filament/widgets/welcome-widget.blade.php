@php
    $user = $this->getUser();
    $fullName = $user?->full_name ?: ($user?->name ?? 'Utilisateur');
@endphp

<x-filament-widgets::widget class="fi-welcome-widget">
    <x-filament::section class="fi-welcome-section">
        <div class="fi-welcome-inner">
            <div class="fi-welcome-avatar" aria-hidden="true">
                @if ($user?->photo_url)
                    <img src="{{ $user->photo_url }}" alt="">
                @else
                    {{ strtoupper(substr($fullName, 0, 1)) }}
                @endif
            </div>

            <div class="fi-welcome-copy">
                <p class="fi-welcome-eyebrow">Bonjour</p>
                <h2 class="fi-welcome-heading">
                    Bienvenue, <span>{{ $fullName }}</span>
                </h2>
                <p class="fi-welcome-text">
                    @if ($user?->isAdmin())
                        Vous êtes connecté à l’espace administration MarketPlace.
                    @else
                        Vous êtes connecté à votre espace vendeur MarketPlace.
                    @endif
                </p>
            </div>
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
