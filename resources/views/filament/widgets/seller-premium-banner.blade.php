@php
    $user = $this->getUser();
    $isPremium = $user?->isPremium();
    $isExpired = $user?->isPremiumExpired();
    $whatsappUrl = \App\Models\User::premiumWhatsappConfirmationUrl();
    $publishedCount = $user?->publishedProductsCount() ?? 0;
@endphp

<div x-data="{ showModal: false }" class="fi-seller-premium-widget">
    @if ($isPremium)
        <div class="relative overflow-hidden rounded-2xl bg-gradient-to-r from-emerald-600 via-teal-600 to-cyan-700 p-6 text-white shadow-lg">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div class="flex items-center gap-4">
                    <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-white/20 backdrop-blur-md text-amber-300">
                        <svg class="h-7 w-7 fill-current" viewBox="0 0 24 24">
                            <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                        </svg>
                    </div>
                    <div>
                        <div class="flex items-center gap-2">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold uppercase tracking-wider bg-amber-400 text-slate-900">
                                Statut Premium Actif
                            </span>
                            <span class="text-xs text-emerald-100">
                                @if ($user->premium_expires_at)
                                    Expire le {{ $user->premium_expires_at->format('d/m/Y') }}
                                @else
                                    Illimité
                                @endif
                            </span>
                        </div>
                        <h3 class="mt-1 text-lg font-bold text-white">
                            Publications illimitées débloquées
                        </h3>
                        <p class="text-xs text-emerald-100 mt-0.5">
                            Vous pouvez publier autant de produits que vous le souhaitez sur Raaga.
                        </p>
                    </div>
                </div>

                <div class="flex items-center gap-2 self-start md:self-auto">
                    <button type="button" @click="showModal = true"
                            class="inline-flex items-center gap-1.5 px-3.5 py-2 text-xs font-semibold text-emerald-950 bg-white hover:bg-emerald-50 active:bg-emerald-100 rounded-lg shadow-sm transition">
                        <svg class="w-4 h-4 text-emerald-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Détails de l'abonnement
                    </button>
                </div>
            </div>
        </div>
    @elseif ($isExpired)
        <div class="relative overflow-hidden rounded-2xl bg-gradient-to-r from-amber-600 via-orange-600 to-rose-600 p-6 text-white shadow-lg">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div class="flex items-center gap-4">
                    <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-white/20 backdrop-blur-md text-white">
                        <svg class="h-7 w-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                    </div>
                    <div>
                        <div class="flex items-center gap-2">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold uppercase tracking-wider bg-rose-200 text-rose-900">
                                Premium Expiré
                            </span>
                            <span class="text-xs text-amber-100">
                                Expiré le {{ $user->premium_expires_at?->format('d/m/Y') }}
                            </span>
                        </div>
                        <h3 class="mt-1 text-lg font-bold text-white">
                            Renouvelez votre abonnement pour publier en illimité
                        </h3>
                        <p class="text-xs text-amber-100 mt-0.5">
                            Pour continuer à publier de nouveaux produits sans restriction, renouvelez pour 5 050 FCFA / an.
                        </p>
                    </div>
                </div>

                <button type="button" @click="showModal = true"
                        class="inline-flex items-center justify-center gap-2 px-5 py-2.5 text-sm font-bold text-slate-900 bg-amber-300 hover:bg-amber-200 active:bg-amber-400 rounded-xl shadow-md transition shrink-0">
                    <svg class="w-4 h-4 text-amber-900" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                    </svg>
                    Renouveler Premium (5 050 FCFA)
                </button>
            </div>
        </div>
    @else
        <div class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-indigo-900 via-purple-900 to-slate-900 p-6 text-white shadow-xl border border-indigo-700/50">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-6">
                <div class="flex items-start sm:items-center gap-4">
                    <div class="flex h-14 w-14 shrink-0 items-center justify-center rounded-2xl bg-gradient-to-br from-amber-400 to-amber-600 text-slate-950 shadow-md">
                        <svg class="h-8 w-8" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                        </svg>
                    </div>
                    <div>
                        <div class="flex flex-wrap items-center gap-2">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold uppercase tracking-wider bg-white/20 text-indigo-100 backdrop-blur-sm">
                                Compte Standard Gratuit ({{ $publishedCount }}/1 produit publié)
                            </span>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-amber-400 text-slate-950">
                                5 050 FCFA / an
                            </span>
                        </div>
                        <h3 class="mt-2 text-xl font-extrabold text-white">
                            Publiez des produits en illimité toute l'année !
                        </h3>
                        <p class="mt-1 text-sm text-indigo-200 leading-relaxed max-w-2xl">
                            Pour publier autant de produits que vous voulez sur Raaga, versez une somme de <strong class="text-amber-300 font-semibold">5 050 FCFA</strong> valable pour toute l'année (12 mois).
                        </p>
                    </div>
                </div>

                <div class="shrink-0">
                    <button type="button" @click="showModal = true"
                            class="inline-flex items-center justify-center gap-2 px-5 py-3 text-sm font-bold text-slate-950 bg-gradient-to-r from-amber-300 via-amber-400 to-yellow-400 hover:from-amber-200 hover:to-yellow-300 active:scale-[0.98] rounded-xl shadow-lg transition duration-150">
                        <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24">
                            <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                        </svg>
                        Passer en Premium (5 050 FCFA)
                    </button>
                </div>
            </div>
        </div>
    @endif

    {{-- Payment Instructions Modal --}}
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
             class="fixed inset-0 bg-black/60 backdrop-blur-sm transition-opacity"
             @click="showModal = false"></div>

        <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
            <div x-show="showModal"
                 x-transition:enter="ease-out duration-200"
                 x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave="ease-in duration-150"
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 class="relative transform overflow-hidden rounded-2xl bg-white dark:bg-gray-900 text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-lg border border-gray-200 dark:border-gray-800">
                
                {{-- Header --}}
                <div class="flex items-center justify-between border-b border-gray-200 dark:border-gray-800 px-6 py-4 bg-gray-50/50 dark:bg-gray-800/50">
                    <div class="flex items-center gap-2">
                        <span class="p-1.5 rounded-lg bg-amber-500 text-slate-950 font-bold">
                            <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24">
                                <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                            </svg>
                        </span>
                        <h3 class="text-base font-bold text-gray-900 dark:text-white">
                            Abonnement Premium annuel
                        </h3>
                    </div>
                    <button type="button" @click="showModal = false" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                {{-- Content --}}
                <div class="p-6">
                    @include('filament.modals.premium-info')
                </div>

                {{-- Footer --}}
                <div class="border-t border-gray-200 dark:border-gray-800 px-6 py-3 bg-gray-50/50 dark:bg-gray-800/50 flex justify-end">
                    <button type="button" @click="showModal = false"
                            class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800 rounded-lg transition">
                        Fermer
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
