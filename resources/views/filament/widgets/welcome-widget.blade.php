@php
    $user = $this->getUser();
    $fullName = $user?->full_name ?: ($user?->name ?? 'Utilisateur');
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
                <div class="flex items-center gap-2">
                    <p class="fi-welcome-eyebrow">Bonjour</p>
                    @if ($isPremium)
                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider bg-amber-100 text-amber-800 border border-amber-300/60 dark:bg-amber-950/60 dark:text-amber-300 dark:border-amber-800/60">
                            <svg class="w-2.5 h-2.5 fill-current" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M11.3 1.046A1 1 0 0112 2v5h4a1 1 0 01.82 1.573l-7 10A1 1 0 018 18v-5H4a1 1 0 01-.82-1.573l7-10a1 1 0 011.12-.38z" clip-rule="evenodd" />
                            </svg>
                            Premium
                        </span>
                    @elseif ($isAdmin)
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider bg-rose-100 text-rose-800 border border-rose-300/60 dark:bg-rose-950/60 dark:text-rose-300">
                            Admin
                        </span>
                    @else
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-semibold text-slate-600 bg-slate-100 dark:bg-slate-800 dark:text-slate-400">
                            Vendeur Standard
                        </span>
                    @endif
                </div>

                <h2 class="fi-welcome-heading">
                    Bienvenue, <span>{{ $fullName }}</span>
                </h2>
                <p class="fi-welcome-text">
                    @if ($isAdmin)
                        Vous êtes connecté à l’espace administration Raaga.
                    @else
                        Gérez vos annonces, suivez leur visibilité et publiez en toute simplicité sur Raaga.
                    @endif
                </p>
            </div>
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
