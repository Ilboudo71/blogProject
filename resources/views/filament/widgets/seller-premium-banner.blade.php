@php
    $user = $this->getUser();
    $isPremium = $user?->isPremium();
    $isExpired = $user?->isPremiumExpired();
    $publishedCount = $user?->publishedProductsCount() ?? 0;
@endphp

<div x-data="{ showModal: false }" class="fi-seller-premium-widget">
    @if ($isPremium)
        {{-- Premium Active Banner --}}
        <div class="premium-banner-card is-active">
            <div class="premium-banner-layout">
                <div class="premium-banner-left">
                    <div class="premium-banner-icon is-active">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" />
                        </svg>
                    </div>
                    <div class="premium-banner-content">
                        <div class="premium-banner-badges">
                            <span class="premium-badge active">
                                {{ __('Vendeur Premium Actif') }}
                            </span>
                            <span class="premium-badge-info">
                                @if ($user->premium_expires_at)
                                    {{ __('Valable jusqu’au') }} {{ $user->premium_expires_at->format('d/m/Y') }}
                                @else
                                    {{ __('Illimité') }}
                                @endif
                            </span>
                        </div>
                        <h3 class="premium-banner-title">
                            {{ __('Publication illimitée active sur Raaga') }}
                        </h3>
                        <p class="premium-banner-desc">
                            {{ __('Votre compte bénéficie de toutes les fonctionnalités et de l\'exposition illimitée pour vos produits.') }}
                        </p>
                    </div>
                </div>

                <div class="premium-banner-actions">
                    <button type="button" @click="showModal = true" class="premium-banner-white-btn">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span>{{ __('Détails de l\'offre') }}</span>
                    </button>
                </div>
            </div>
        </div>
    @elseif ($isExpired)
        {{-- Premium Expired Banner --}}
        <div class="premium-banner-card is-expired">
            <div class="premium-banner-layout">
                <div class="premium-banner-left">
                    <div class="premium-banner-icon is-expired">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                    </div>
                    <div class="premium-banner-content">
                        <div class="premium-banner-badges">
                            <span class="premium-badge expired">
                                {{ __('Abonnement Expiré') }}
                            </span>
                            <span class="premium-badge-info expired">
                                {{ __('Expiré le') }} {{ $user->premium_expires_at?->format('d/m/Y') }}
                            </span>
                        </div>
                        <h3 class="premium-banner-title">
                            {{ __('Renouvelez votre statut Premium pour continuer en illimité') }}
                        </h3>
                        <p class="premium-banner-desc expired">
                            {{ __('Vos annonces existantes restent actives. Renouvelez pour 5 050 FCFA / an pour en publier de nouvelles.') }}
                        </p>
                    </div>
                </div>

                <div class="premium-banner-actions">
                    <button type="button" @click="showModal = true" class="premium-banner-btn">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                        </svg>
                        <span>{{ __('Renouveler (5 050 FCFA)') }}</span>
                    </button>
                </div>
            </div>
        </div>
    @else
        {{-- Standard / Free User Banner --}}
        <div class="premium-banner-card">
            <div class="premium-banner-layout">
                <div class="premium-banner-left">
                    <div class="premium-banner-icon">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" />
                        </svg>
                    </div>

                    <div class="premium-banner-content">
                        <div class="premium-banner-badges">
                            <span class="premium-badge standard">
                                {{ __('Compte Gratuit') }} ({{ $publishedCount }}/1 {{ __('produit publié') }})
                            </span>
                            <span class="premium-badge price">
                                5 050 FCFA / {{ __('an') }}
                            </span>
                        </div>

                        <h3 class="premium-banner-title">
                            {{ __('Publiez des produits en illimité toute l\'année !') }}
                        </h3>

                        <p class="premium-banner-desc">
                            {{ __('Pour exposer autant de produits que vous le souhaitez sur Raaga, activez votre accès Premium pour seulement') }} <strong class="premium-highlight">5 050 FCFA {{ __('pour toute l\'année') }}</strong> ({{ __('12 mois complets') }}).
                        </p>
                    </div>
                </div>

                <div class="premium-banner-actions">
                    <button type="button" @click="showModal = true" class="premium-banner-btn">
                        <svg fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M11.3 1.046A1 1 0 0112 2v5h4a1 1 0 01.82 1.573l-7 10A1 1 0 018 18v-5H4a1 1 0 01-.82-1.573l7-10a1 1 0 011.12-.38z" clip-rule="evenodd" />
                        </svg>
                        <span>{{ __('Passer en Premium (5 050 FCFA)') }}</span>
                    </button>
                </div>
            </div>
        </div>
    @endif

    {{-- Custom Styled Premium Modal (Scrollable, fully visible buttons, responsive) --}}
    <div x-show="showModal"
         x-cloak
         style="display: none; position: fixed; inset: 0; z-index: 9999; overflow-y: auto;"
         role="dialog"
         aria-modal="true"
         @keydown.escape.window="showModal = false">
        
        {{-- Backdrop --}}
        <div x-show="showModal"
             x-transition:enter="ease-out duration-200"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="ease-in duration-150"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             style="position: fixed; inset: 0; background: rgba(15, 23, 42, 0.82); backdrop-filter: blur(5px); transition: opacity 0.2s ease;"
             @click="showModal = false"></div>

        <div class="pm-modal-container">
            <div x-show="showModal"
                 x-transition:enter="ease-out duration-200"
                 x-transition:enter-start="opacity-0 translate-y-4"
                 x-transition:enter-end="opacity-100 translate-y-0"
                 x-transition:leave="ease-in duration-150"
                 x-transition:leave-start="opacity-100 translate-y-0"
                 x-transition:leave-end="opacity-0 translate-y-4"
                 class="pm-modal-dialog">
                
                {{-- Modal Header (Fixed) --}}
                <div class="pm-modal-header">
                    <div style="display: flex; align-items: center; gap: 0.75rem; min-width: 0;">
                        <span class="pm-modal-header-icon">
                            <svg style="width: 1.25rem; height: 1.25rem;" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M11.3 1.046A1 1 0 0112 2v5h4a1 1 0 01.82 1.573l-7 10A1 1 0 018 18v-5H4a1 1 0 01-.82-1.573l7-10a1 1 0 011.12-.38z" clip-rule="evenodd" />
                            </svg>
                        </span>
                        <div style="min-width: 0;">
                            <h3 style="margin: 0; font-size: clamp(1.05rem, 3.5vw, 1.2rem); font-weight: 800; color: #0f172a; line-height: 1.25; overflow-wrap: break-word;">
                                {{ __('Abonnement Premium Raaga') }}
                            </h3>
                            <p style="margin: 0.15rem 0 0; font-size: 0.82rem; color: #64748b; font-weight: 500;">{{ __('Paiement & Activation immédiate') }}</p>
                        </div>
                    </div>
                    <button type="button" @click="showModal = false"
                            class="pm-modal-close-btn" aria-label="Fermer">
                        <svg style="width: 1.35rem; height: 1.35rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                {{-- Modal Body (Scrollable with ample padding) --}}
                <div class="pm-modal-body">
                    @include('filament.modals.premium-info')
                </div>

                {{-- Modal Footer (Fixed) --}}
                <div class="pm-modal-footer">
                    <button type="button" @click="showModal = false" class="pm-modal-footer-btn">
                        {{ __('Fermer la fenêtre') }}
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
