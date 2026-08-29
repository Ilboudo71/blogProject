@php
    $whatsappUrl = \App\Models\User::premiumWhatsappConfirmationUrl();
    $paymentNumber = \App\Models\User::PREMIUM_PAYMENT_NUMBER;
    $confirmationNumber = \App\Models\User::PREMIUM_CONFIRMATION_NUMBER;
@endphp

<div x-data="{
    copiedPay: false,
    copiedConfirm: false,
    copy(text, target) {
        if (navigator.clipboard && window.isSecureContext) {
            navigator.clipboard.writeText(text);
        } else {
            const input = document.createElement('textarea');
            input.value = text;
            input.style.position = 'fixed';
            input.style.opacity = '0';
            document.body.appendChild(input);
            input.focus();
            input.select();
            document.execCommand('copy');
            document.body.removeChild(input);
        }
        if (target === 'pay') {
            this.copiedPay = true;
            setTimeout(() => this.copiedPay = false, 2500);
        } else {
            this.copiedConfirm = true;
            setTimeout(() => this.copiedConfirm = false, 2500);
        }
    }
}" class="premium-modal-wrapper space-y-4 text-slate-800 dark:text-slate-100">

    {{-- Hero Offer Card --}}
    <div class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-teal-800 via-teal-700 to-emerald-800 p-5 text-white shadow-md">
        <div class="relative z-10 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div>
                <div class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-amber-400/20 text-amber-200 border border-amber-300/30 text-xs font-semibold uppercase tracking-wider mb-2">
                    <svg class="w-3.5 h-3.5 text-amber-300" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M11.3 1.046A1 1 0 0112 2v5h4a1 1 0 01.82 1.573l-7 10A1 1 0 018 18v-5H4a1 1 0 01-.82-1.573l7-10a1 1 0 011.12-.38z" clip-rule="evenodd" />
                    </svg>
                    Abonnement Vendeur Pro
                </div>
                <h4 class="text-xl font-bold tracking-tight text-white">
                    Passez au statut Premium
                </h4>
                <p class="text-xs text-teal-100/90 mt-0.5 max-w-sm">
                    Publiez autant de produits que vous voulez sur Raaga sans aucune limite.
                </p>
            </div>

            <div class="sm:text-right bg-white/10 backdrop-blur-md rounded-xl p-3 border border-white/15 shrink-0">
                <span class="block text-xs uppercase text-teal-200 font-medium">Tarif Annuel</span>
                <div class="text-2xl font-black text-amber-300 leading-none mt-1">
                    5 050 <span class="text-xs font-semibold text-white">FCFA</span>
                </div>
                <span class="text-[11px] text-teal-100">Valable 12 mois complets</span>
            </div>
        </div>

        {{-- Feature Highlights --}}
        <div class="relative z-10 grid grid-cols-2 sm:grid-cols-3 gap-2 mt-4 pt-3 border-t border-white/15 text-xs text-teal-100">
            <div class="flex items-center gap-1.5">
                <svg class="w-4 h-4 text-amber-300 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                </svg>
                <span>Produits illimités</span>
            </div>
            <div class="flex items-center gap-1.5">
                <svg class="w-4 h-4 text-amber-300 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                </svg>
                <span>Activation rapide</span>
            </div>
            <div class="flex items-center gap-1.5 col-span-2 sm:col-span-1">
                <svg class="w-4 h-4 text-amber-300 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                </svg>
                <span>Support dédié</span>
            </div>
        </div>
    </div>

    {{-- Step 1 : Orange Money --}}
    <div class="rounded-xl border border-orange-200/80 bg-gradient-to-r from-orange-50/70 to-amber-50/50 p-4 dark:border-orange-900/50 dark:from-orange-950/30 dark:to-amber-950/20">
        <div class="flex items-start justify-between gap-3">
            <div class="flex items-start gap-3">
                <div class="flex h-7 w-7 shrink-0 items-center justify-center rounded-lg bg-orange-500 text-white font-bold text-xs shadow-sm">
                    1
                </div>
                <div>
                    <div class="flex items-center gap-2">
                        <span class="text-xs font-bold uppercase tracking-wider text-orange-800 dark:text-orange-300 bg-orange-100 dark:bg-orange-900/50 px-2 py-0.5 rounded">
                            Orange Money
                        </span>
                        <span class="text-xs font-semibold text-slate-700 dark:text-slate-300">Paiement 5 050 FCFA</span>
                    </div>
                    <p class="text-xs text-slate-600 dark:text-slate-400 mt-1">
                        Effectuez le transfert vers le numéro de compte suivant :
                    </p>
                    <div class="mt-2 inline-flex items-center gap-2 bg-white dark:bg-slate-900 px-3 py-1.5 rounded-lg border border-orange-200 dark:border-orange-800/60 shadow-xs">
                        <span class="text-base font-black tracking-wider text-slate-900 dark:text-white font-mono">
                            {{ $paymentNumber }}
                        </span>
                    </div>
                </div>
            </div>

            <button type="button"
                    @click="copy('{{ str_replace(' ', '', $paymentNumber) }}', 'pay')"
                    class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-semibold rounded-lg bg-white dark:bg-slate-800 text-orange-700 dark:text-orange-300 border border-orange-300 dark:border-orange-700 hover:bg-orange-50 dark:hover:bg-slate-700 shadow-xs transition active:scale-95 shrink-0">
                <template x-if="!copiedPay">
                    <span class="flex items-center gap-1">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                        </svg>
                        Copier
                    </span>
                </template>
                <template x-if="copiedPay">
                    <span class="flex items-center gap-1 text-emerald-600 dark:text-emerald-400 font-bold">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                        </svg>
                        Copié !
                    </span>
                </template>
            </button>
        </div>
    </div>

    {{-- Step 2 : Confirmation & Screenshot --}}
    <div class="rounded-xl border border-teal-200/80 bg-gradient-to-r from-teal-50/70 to-emerald-50/50 p-4 dark:border-teal-900/50 dark:from-teal-950/30 dark:to-emerald-950/20">
        <div class="flex items-start justify-between gap-3">
            <div class="flex items-start gap-3">
                <div class="flex h-7 w-7 shrink-0 items-center justify-center rounded-lg bg-teal-600 text-white font-bold text-xs shadow-sm">
                    2
                </div>
                <div>
                    <div class="flex items-center gap-2">
                        <span class="text-xs font-bold uppercase tracking-wider text-teal-800 dark:text-teal-300 bg-teal-100 dark:bg-teal-900/50 px-2 py-0.5 rounded">
                            Confirmation
                        </span>
                        <span class="text-xs font-semibold text-slate-700 dark:text-slate-300">Envoi de la capture</span>
                    </div>
                    <p class="text-xs text-slate-600 dark:text-slate-400 mt-1">
                        Numéro de confirmation WhatsApp / Appel :
                    </p>
                    <div class="mt-2 inline-flex items-center gap-2 bg-white dark:bg-slate-900 px-3 py-1.5 rounded-lg border border-teal-200 dark:border-teal-800/60 shadow-xs">
                        <span class="text-base font-black tracking-wider text-slate-900 dark:text-white font-mono">
                            {{ $confirmationNumber }}
                        </span>
                    </div>
                </div>
            </div>

            <button type="button"
                    @click="copy('{{ str_replace(' ', '', $confirmationNumber) }}', 'confirm')"
                    class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-semibold rounded-lg bg-white dark:bg-slate-800 text-teal-700 dark:text-teal-300 border border-teal-300 dark:border-teal-700 hover:bg-teal-50 dark:hover:bg-slate-700 shadow-xs transition active:scale-95 shrink-0">
                <template x-if="!copiedConfirm">
                    <span class="flex items-center gap-1">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                        </svg>
                        Copier
                    </span>
                </template>
                <template x-if="copiedConfirm">
                    <span class="flex items-center gap-1 text-emerald-600 dark:text-emerald-400 font-bold">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                        </svg>
                        Copié !
                    </span>
                </template>
            </button>
        </div>

        <div class="mt-3 rounded-lg bg-white/80 dark:bg-slate-900/70 p-3 border border-teal-100 dark:border-teal-900/40 text-xs text-slate-600 dark:text-slate-300">
            <span class="font-semibold text-teal-900 dark:text-teal-200">Consigne :</span>
            « Après avoir effectué votre paiement, veuillez envoyer une capture d’écran de la transaction au <strong>74 65 09 24</strong> afin que votre compte Premium puisse être activé. »
        </div>
    </div>

    {{-- Access Buttons --}}
    <div class="pt-2 flex flex-col sm:flex-row gap-2.5">
        <a href="{{ $whatsappUrl }}" target="_blank" rel="noopener noreferrer"
           class="flex-1 inline-flex items-center justify-center gap-2 px-4 py-3 text-sm font-bold text-white bg-emerald-600 hover:bg-emerald-500 active:bg-emerald-700 rounded-xl shadow-md transition-all duration-150 hover:-translate-y-0.5">
            <svg class="w-5 h-5 fill-current shrink-0" viewBox="0 0 24 24">
                <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/>
            </svg>
            <span>Envoyer la capture sur WhatsApp</span>
        </a>

        <a href="tel:+226{{ str_replace(' ', '', $confirmationNumber) }}"
           class="inline-flex items-center justify-center gap-2 px-4 py-3 text-sm font-semibold text-slate-700 dark:text-slate-200 bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 rounded-xl border border-slate-200 dark:border-slate-700 transition">
            <svg class="w-4 h-4 text-slate-500 dark:text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
            </svg>
            <span>Appeler le support</span>
        </a>
    </div>
</div>
