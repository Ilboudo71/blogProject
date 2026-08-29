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
            <div style="display: flex; justify-content: space-between; align-items: center; gap: 1rem; flex-wrap: wrap;">
                <div style="display: flex; align-items: center; gap: 1rem;">
                    <div style="width: 3.25rem; height: 3.25rem; border-radius: 0.85rem; background: rgba(255, 255, 255, 0.15); display: flex; align-items: center; justify-content: center; color: #fde047; border: 1px solid rgba(255, 255, 255, 0.25); flex-shrink: 0;">
                        <svg style="width: 1.75rem; height: 1.75rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" />
                        </svg>
                    </div>
                    <div>
                        <div style="display: flex; align-items: center; gap: 0.5rem; flex-wrap: wrap;">
                            <span style="display: inline-block; padding: 0.2rem 0.6rem; border-radius: 999px; font-size: 0.72rem; font-weight: 800; text-transform: uppercase; background: #facc15; color: #0f172a;">
                                Vendeur Premium Actif
                            </span>
                            <span style="font-size: 0.8rem; color: #ccfbf1;">
                                @if ($user->premium_expires_at)
                                    Valable jusqu’au {{ $user->premium_expires_at->format('d/m/Y') }}
                                @else
                                    Illimité
                                @endif
                            </span>
                        </div>
                        <h3 style="margin: 0.35rem 0 0; font-size: 1.15rem; font-weight: 800; color: #ffffff;">
                            Publication illimitée active sur Raaga
                        </h3>
                        <p style="margin: 0.2rem 0 0; font-size: 0.82rem; color: #ccfbf1; opacity: 0.9;">
                            Votre compte bénéficie de toutes les fonctionnalités et de l'exposition illimitée pour vos produits.
                        </p>
                    </div>
                </div>

                <div>
                    <button type="button" @click="showModal = true"
                            style="display: inline-flex; align-items: center; gap: 0.45rem; padding: 0.65rem 1.1rem; border-radius: 0.75rem; font-size: 0.82rem; font-weight: 700; color: #0f766e; background: #ffffff; border: none; cursor: pointer; box-shadow: 0 4px 12px rgba(0,0,0,0.1);">
                        <svg style="width: 1rem; height: 1rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Détails de l'offre
                    </button>
                </div>
            </div>
        </div>
    @elseif ($isExpired)
        {{-- Premium Expired Banner --}}
        <div class="premium-banner-card is-expired">
            <div style="display: flex; justify-content: space-between; align-items: center; gap: 1rem; flex-wrap: wrap;">
                <div style="display: flex; align-items: center; gap: 1rem;">
                    <div style="width: 3.25rem; height: 3.25rem; border-radius: 0.85rem; background: rgba(255, 255, 255, 0.2); display: flex; align-items: center; justify-content: center; color: #ffffff; border: 1px solid rgba(255, 255, 255, 0.3); flex-shrink: 0;">
                        <svg style="width: 1.75rem; height: 1.75rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                    </div>
                    <div>
                        <div style="display: flex; align-items: center; gap: 0.5rem; flex-wrap: wrap;">
                            <span style="display: inline-block; padding: 0.2rem 0.6rem; border-radius: 999px; font-size: 0.72rem; font-weight: 800; text-transform: uppercase; background: #fecdd3; color: #881337;">
                                Abonnement Expiré
                            </span>
                            <span style="font-size: 0.8rem; color: #ffedd5;">
                                Expiré le {{ $user->premium_expires_at?->format('d/m/Y') }}
                            </span>
                        </div>
                        <h3 style="margin: 0.35rem 0 0; font-size: 1.15rem; font-weight: 800; color: #ffffff;">
                            Renouvelez votre statut Premium pour continuer en illimité
                        </h3>
                        <p style="margin: 0.2rem 0 0; font-size: 0.82rem; color: #ffedd5; opacity: 0.9;">
                            Vos annonces existantes restent actives. Renouvelez pour 5 050 FCFA / an pour en publier de nouvelles.
                        </p>
                    </div>
                </div>

                <div>
                    <button type="button" @click="showModal = true"
                            class="premium-banner-btn">
                        <svg style="width: 1rem; height: 1rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                        </svg>
                        Renouveler (5 050 FCFA)
                    </button>
                </div>
            </div>
        </div>
    @else
        {{-- Standard / Free User Banner (Highly Attractive & Standalone styled) --}}
        <div class="premium-banner-card">
            <div style="display: flex; justify-content: space-between; align-items: center; gap: 1.5rem; flex-wrap: wrap;">
                <div style="display: flex; align-items: center; gap: 1.15rem; flex: 1; min-width: 260px;">
                    <div style="width: 3.5rem; height: 3.5rem; border-radius: 1rem; background: linear-gradient(135deg, #0f766e 0%, #0d9488 100%); display: flex; align-items: center; justify-content: center; color: #fde047; border: 1px solid rgba(45, 212, 191, 0.4); box-shadow: 0 8px 20px rgba(15, 118, 110, 0.3); flex-shrink: 0;">
                        <svg style="width: 1.85rem; height: 1.85rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" />
                        </svg>
                    </div>

                    <div>
                        <div style="display: flex; align-items: center; gap: 0.5rem; flex-wrap: wrap;">
                            <span style="display: inline-block; padding: 0.2rem 0.65rem; border-radius: 999px; font-size: 0.72rem; font-weight: 700; text-transform: uppercase; background: rgba(15, 118, 110, 0.6); color: #99f6e4; border: 1px solid rgba(45, 212, 191, 0.3);">
                                Compte Gratuit ({{ $publishedCount }}/1 produit publié)
                            </span>
                            <span style="display: inline-block; padding: 0.2rem 0.65rem; border-radius: 999px; font-size: 0.72rem; font-weight: 800; background: rgba(253, 224, 71, 0.2); color: #fef08a; border: 1px solid rgba(253, 224, 71, 0.35);">
                                5 050 FCFA / an
                            </span>
                        </div>

                        <h3 style="margin: 0.45rem 0 0; font-size: 1.35rem; font-weight: 900; color: #ffffff; letter-spacing: -0.01em;">
                            Publiez des produits en illimité toute l'année !
                        </h3>

                        <p style="margin: 0.3rem 0 0; font-size: 0.88rem; color: #cbd5e1; line-height: 1.5; max-width: 620px;">
                            Pour exposer autant de produits que vous le souhaitez sur Raaga, activez votre accès Premium pour seulement <strong style="color: #fde047; font-weight: 800;">5 050 FCFA pour toute l'année</strong> (12 mois complets).
                        </p>
                    </div>
                </div>

                <div style="flex-shrink: 0;">
                    <button type="button" @click="showModal = true"
                            class="premium-banner-btn">
                        <svg style="width: 1rem; height: 1rem;" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M11.3 1.046A1 1 0 0112 2v5h4a1 1 0 01.82 1.573l-7 10A1 1 0 018 18v-5H4a1 1 0 01-.82-1.573l7-10a1 1 0 011.12-.38z" clip-rule="evenodd" />
                        </svg>
                        <span>Passer en Premium (5 050 FCFA)</span>
                    </button>
                </div>
            </div>
        </div>
    @endif

    {{-- Custom Styled Premium Modal --}}
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
             style="position: fixed; inset: 0; background: rgba(15, 23, 42, 0.75); backdrop-filter: blur(4px); transition: opacity 0.2s ease;"
             @click="showModal = false"></div>

        <div style="display: flex; min-height: 100vh; align-items: center; justify-content: center; padding: 1rem;">
            <div x-show="showModal"
                 x-transition:enter="ease-out duration-200"
                 x-transition:enter-start="opacity-0 translate-y-4"
                 x-transition:enter-end="opacity-100 translate-y-0"
                 x-transition:leave="ease-in duration-150"
                 x-transition:leave-start="opacity-100 translate-y-0"
                 x-transition:leave-end="opacity-0 translate-y-4"
                 style="position: relative; width: 100%; max-width: 540px; background: #ffffff; border-radius: 1.25rem; box-shadow: 0 25px 60px rgba(0,0,0,0.3); border: 1px solid rgba(226, 232, 240, 0.9); overflow: hidden; text-align: left; z-index: 10000;">
                
                {{-- Modal Header --}}
                <div style="display: flex; align-items: center; justify-content: space-between; padding: 1rem 1.4rem; background: #f8fafc; border-bottom: 1px solid #e2e8f0;">
                    <div style="display: flex; align-items: center; gap: 0.65rem;">
                        <span style="width: 2rem; height: 2rem; border-radius: 0.5rem; background: linear-gradient(135deg, #facc15, #eab308); color: #0f172a; display: flex; align-items: center; justify-content: center;">
                            <svg style="width: 1.1rem; height: 1.1rem;" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M11.3 1.046A1 1 0 0112 2v5h4a1 1 0 01.82 1.573l-7 10A1 1 0 018 18v-5H4a1 1 0 01-.82-1.573l7-10a1 1 0 011.12-.38z" clip-rule="evenodd" />
                            </svg>
                        </span>
                        <div>
                            <h3 style="margin: 0; font-size: 1rem; font-weight: 800; color: #0f172a; line-height: 1.2;">
                                Abonnement Premium Raaga
                            </h3>
                            <p style="margin: 0.15rem 0 0; font-size: 0.75rem; color: #64748b;">Paiement & Activation immédiate</p>
                        </div>
                    </div>
                    <button type="button" @click="showModal = false"
                            style="padding: 0.4rem; border-radius: 0.5rem; color: #94a3b8; background: transparent; border: none; cursor: pointer;">
                        <svg style="width: 1.25rem; height: 1.25rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                {{-- Modal Body --}}
                <div style="padding: 1.4rem;">
                    @include('filament.modals.premium-info')
                </div>

                {{-- Modal Footer --}}
                <div style="display: flex; justify-content: flex-end; padding: 0.85rem 1.4rem; background: #f8fafc; border-top: 1px solid #e2e8f0;">
                    <button type="button" @click="showModal = false"
                            style="padding: 0.5rem 1rem; border-radius: 0.6rem; font-size: 0.8rem; font-weight: 600; color: #475569; background: #e2e8f0; border: none; cursor: pointer;">
                        Fermer la fenêtre
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
