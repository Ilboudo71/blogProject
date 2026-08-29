@php
    $user = $this->getUser();
    $isPremium = $user?->isPremium();
    $isExpired = $user?->isPremiumExpired();
    $publishedCount = $user?->publishedProductsCount() ?? 0;
@endphp

<div x-data="{ showModal: false }" class="fi-seller-premium-widget">
    @if ($isPremium)
        {{-- Premium Active Banner --}}
        <div class="relative overflow-hidden rounded-2xl bg-gradient-to-r from-teal-700 via-emerald-700 to-teal-800 p-6 text-white shadow-lg border border-teal-600/40">
            <div class="relative z-10 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div class="flex items-center gap-4">
                    <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-white/15 backdrop-blur-md text-amber-300 border border-white/20">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" />
                        </svg>
                    </div>
                    <div>
                        <div class="flex flex-wrap items-center gap-2">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold uppercase tracking-wider bg-amber-400 text-teal-950 shadow-xs">
                                Vendeur Premium Actif
                            </span>
                            <span class="text-xs text-teal-100 font-medium">
                                @if ($user->premium_expires_at)
                                    Valable jusqu’au {{ $user->premium_expires_at->format('d/m/Y') }}
                                @else
                                    Illimité
                                @endif
                            </span>
                        </div>
                        <h3 class="mt-1 text-lg font-bold text-white">
                            Publication illimitée active sur Raaga
                        </h3>
                        <p class="text-xs text-teal-100 mt-0.5">
                            Votre compte bénéficie de toutes les fonctionnalités et de l'exposition illimitée pour vos produits.
                        </p>
                    </div>
                </div>

                <div class="flex items-center gap-2 shrink-0">
                    <button type="button" @click="showModal = true"
                            class="inline-flex items-center gap-2 px-4 py-2.5 text-xs font-bold text-teal-950 bg-white hover:bg-teal-50 active:bg-teal-100 rounded-xl shadow-md transition duration-150">
                        <svg class="w-4 h-4 text-teal-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Détails de l'offre
                    </button>
                </div>
            </div>
        </div>
    @elseif ($isExpired)
        {{-- Premium Expired Banner --}}
        <div class="relative overflow-hidden rounded-2xl bg-gradient-to-r from-amber-700 via-orange-600 to-rose-700 p-6 text-white shadow-lg border border-amber-500/30">
            <div class="relative z-10 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div class="flex items-center gap-4">
                    <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-white/20 backdrop-blur-md text-white border border-white/20">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                    </div>
                    <div>
                        <div class="flex items-center gap-2">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold uppercase tracking-wider bg-rose-200 text-rose-950">
                                Abonnement Expiré
                            </span>
                            <span class="text-xs text-amber-100">
                                Expiré le {{ $user->premium_expires_at?->format('d/m/Y') }}
                            </span>
                        </div>
                        <h3 class="mt-1 text-lg font-bold text-white">
                            Renouvelez votre statut Premium pour continuer en illimité
                        </h3>
                        <p class="text-xs text-amber-100 mt-0.5">
                            Vos annonces existantes restent actives. Renouvelez pour 5 050 FCFA / an pour en publier de nouvelles.
                        </p>
                    </div>
                </div>

                <div class="shrink-0">
                    <button type="button" @click="showModal = true"
                            class="inline-flex items-center justify-center gap-2 px-5 py-2.5 text-sm font-bold text-slate-900 bg-amber-300 hover:bg-amber-200 active:bg-amber-400 rounded-xl shadow-md transition shrink-0">
                        <svg class="w-4 h-4 text-slate-900" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                        </svg>
                        Renouveler (5 050 FCFA)
                    </button>
                </div>
            </div>
        </div>
    @else
        {{-- Standard / Free User Banner (Highly Attractive & Clean) --}}
        <div class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-slate-900 via-teal-950 to-slate-900 p-6 sm:p-7 text-white shadow-xl border border-teal-800/40">
            {{-- Ambient Decorative Glow --}}
            <div class="pointer-events-none absolute -right-16 -top-16 h-64 w-64 rounded-full bg-teal-500/15 blur-3xl" aria-hidden="true"></div>
            <div class="pointer-events-none absolute -left-16 -bottom-16 h-64 w-64 rounded-full bg-amber-500/10 blur-3xl" aria-hidden="true"></div>

            <div class="relative z-10 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
                <div class="flex items-start sm:items-center gap-4">
                    <div class="flex h-14 w-14 shrink-0 items-center justify-center rounded-2xl bg-gradient-to-br from-teal-500 to-emerald-600 text-white shadow-lg shadow-teal-950/40 border border-teal-400/30">
                        <svg class="h-7 w-7 text-amber-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" />
                        </svg>
                    </div>

                    <div>
                        <div class="flex flex-wrap items-center gap-2">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold uppercase tracking-wider bg-teal-800/80 text-teal-200 border border-teal-600/40">
                                Compte Gratuit ({{ $publishedCount }}/1 produit publié)
                            </span>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-amber-400/20 text-amber-300 border border-amber-400/30">
                                5 050 FCFA / an
                            </span>
                        </div>

                        <h3 class="mt-2 text-xl sm:text-2xl font-black text-white tracking-tight">
                            Publiez des produits en illimité toute l'année !
                        </h3>

                        <p class="mt-1.5 text-sm text-slate-300 leading-relaxed max-w-2xl">
                            Pour exposer autant de produits que vous le souhaitez sur Raaga, activez votre accès Premium pour seulement <strong class="text-amber-300 font-bold">5 050 FCFA pour toute l'année</strong> (12 mois complets).
                        </p>
                    </div>
                </div>

                <div class="shrink-0 flex items-center">
                    <button type="button" @click="showModal = true"
                            class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-6 py-3.5 text-sm font-bold text-teal-950 bg-gradient-to-r from-amber-300 via-amber-400 to-yellow-400 hover:from-amber-200 hover:to-yellow-300 active:scale-[0.98] rounded-xl shadow-lg shadow-amber-900/20 transition-all duration-150 hover:-translate-y-0.5 cursor-pointer">
                        <svg class="w-4 h-4 text-teal-950 fill-current" viewBox="0 0 20 20">
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
         style="display: none;"
         class="fixed inset-0 z-50 overflow-y-auto"
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
             class="fixed inset-0 bg-slate-950/70 backdrop-blur-md transition-opacity"
             @click="showModal = false"></div>

        <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
            <div x-show="showModal"
                 x-transition:enter="ease-out duration-200"
                 x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave="ease-in duration-150"
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 class="relative transform overflow-hidden rounded-2xl bg-white dark:bg-slate-900 text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-lg border border-slate-200 dark:border-slate-800">
                
                {{-- Modal Header --}}
                <div class="flex items-center justify-between border-b border-slate-200/80 dark:border-slate-800 px-6 py-4 bg-slate-50/80 dark:bg-slate-800/60">
                    <div class="flex items-center gap-2.5">
                        <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-gradient-to-br from-amber-400 to-amber-500 text-teal-950 shadow-xs">
                            <svg class="w-4 h-4 fill-current" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M11.3 1.046A1 1 0 0112 2v5h4a1 1 0 01.82 1.573l-7 10A1 1 0 018 18v-5H4a1 1 0 01-.82-1.573l7-10a1 1 0 011.12-.38z" clip-rule="evenodd" />
                            </svg>
                        </span>
                        <div>
                            <h3 class="text-base font-bold text-slate-900 dark:text-white leading-tight">
                                Abonnement Premium Raaga
                            </h3>
                            <p class="text-xs text-slate-500 dark:text-slate-400">Paiement & Activation immédiate</p>
                        </div>
                    </div>
                    <button type="button" @click="showModal = false"
                            class="rounded-lg p-1.5 text-slate-400 hover:text-slate-700 hover:bg-slate-100 dark:hover:text-slate-200 dark:hover:bg-slate-800 transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                {{-- Modal Body --}}
                <div class="p-6">
                    @include('filament.modals.premium-info')
                </div>

                {{-- Modal Footer --}}
                <div class="border-t border-slate-200/80 dark:border-slate-800 px-6 py-3 bg-slate-50/80 dark:bg-slate-800/60 flex justify-end">
                    <button type="button" @click="showModal = false"
                            class="px-4 py-2 text-xs font-semibold text-slate-700 dark:text-slate-300 hover:bg-slate-200/60 dark:hover:bg-slate-800 rounded-lg transition">
                        Fermer la fenêtre
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
