@php
    $user = $this->getUser();
    $fullName = $user?->full_name ?: ($user?->name ?? __('Utilisateur'));
    $isPremium = $user?->isPremium();
    $isAdmin = $user?->isAdmin();
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
                <div class="fi-welcome-header-row">
                    <p class="fi-welcome-eyebrow">{{ __('Bonjour') }}</p>
                    @if ($isPremium)
                        <span class="fi-role-badge premium">
                            <svg class="fi-role-badge-icon" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M11.3 1.046A1 1 0 0112 2v5h4a1 1 0 01.82 1.573l-7 10A1 1 0 018 18v-5H4a1 1 0 01-.82-1.573l7-10a1 1 0 011.12-.38z" clip-rule="evenodd" />
                            </svg>
                            {{ __('Vendeur Premium') }}
                        </span>
                    @elseif ($isAdmin)
                        <span class="fi-role-badge admin">
                            {{ __('Administrateur') }}
                        </span>
                    @else
                        <span class="fi-role-badge standard">
                            {{ __('Vendeur Standard') }}
                        </span>
                    @endif
                </div>

                <h2 class="fi-welcome-heading">
                    {{ __('Bienvenue') }}, <span>{{ $fullName }}</span>
                </h2>
                <p class="fi-welcome-text">
                    @if ($isAdmin)
                        {{ __('Vous êtes connecté à l’espace administration Raaga.') }}
                    @else
                        {{ __('Gérez vos annonces, suivez leur visibilité et publiez vos articles en toute simplicité sur Raaga.') }}
                    @endif
                </p>
            </div>
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
