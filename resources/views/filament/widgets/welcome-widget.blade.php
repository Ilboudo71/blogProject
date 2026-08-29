@php
    $user = $this->getUser();
    $fullName = $user?->full_name ?: ($user?->name ?? 'Utilisateur');
    $isPremium = $user?->isPremium();
    $isAdmin = $user?->isAdmin();
@endphp

<x-filament-widgets::widget class="fi-welcome-widget">
    <x-filament::section class="fi-welcome-section">
        <div class="fi-welcome-inner" style="padding: 1.5rem 1.75rem;">
            <div class="fi-welcome-avatar" aria-hidden="true" style="width: 4.2rem; height: 4.2rem; font-size: 1.6rem; border-radius: 1.15rem;">
                @if ($user?->photo_url)
                    <img src="{{ $user->photo_url }}" alt="">
                @else
                    {{ strtoupper(substr($fullName, 0, 1)) }}
                @endif
            </div>

            <div class="fi-welcome-copy">
                <div class="flex items-center gap-2.5">
                    <p class="fi-welcome-eyebrow" style="font-size: 0.85rem; font-weight: 800; letter-spacing: 0.08em;">Bonjour</p>
                    @if ($isPremium)
                        <span class="inline-flex items-center gap-1.5 px-3 py-0.5 rounded-full text-xs font-black uppercase tracking-wider bg-amber-100 text-amber-900 border border-amber-300 shadow-xs">
                            <svg class="w-3.5 h-3.5 fill-current text-amber-600" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M11.3 1.046A1 1 0 0112 2v5h4a1 1 0 01.82 1.573l-7 10A1 1 0 018 18v-5H4a1 1 0 01-.82-1.573l7-10a1 1 0 011.12-.38z" clip-rule="evenodd" />
                            </svg>
                            Vendeur Premium
                        </span>
                    @elseif ($isAdmin)
                        <span class="inline-flex items-center px-3 py-0.5 rounded-full text-xs font-black uppercase tracking-wider bg-rose-100 text-rose-900 border border-rose-300">
                            Administrateur
                        </span>
                    @else
                        <span class="inline-flex items-center px-3 py-0.5 rounded-full text-xs font-bold text-slate-700 bg-slate-200/80 border border-slate-300">
                            Vendeur Standard
                        </span>
                    @endif
                </div>

                <h2 class="fi-welcome-heading" style="font-size: 1.65rem; font-weight: 900; margin-top: 0.3rem;">
                    Bienvenue, <span style="color: #0f766e;">{{ $fullName }}</span>
                </h2>
                <p class="fi-welcome-text" style="font-size: 1.05rem; color: #475569; margin-top: 0.35rem; line-height: 1.5;">
                    @if ($isAdmin)
                        Vous êtes connecté à l’espace administration Raaga.
                    @else
                        Gérez vos annonces, suivez leur visibilité et publiez vos articles en toute simplicité sur Raaga.
                    @endif
                </p>
            </div>
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
