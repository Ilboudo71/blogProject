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
}" class="premium-modal-box space-y-5 text-slate-800 dark:text-slate-100" style="font-size: 1rem; line-height: 1.6; padding-bottom: 0.5rem;">

    {{-- Hero Offer Card --}}
    <div class="pm-hero" style="background: linear-gradient(135deg, #115e59 0%, #0f766e 50%, #065f46 100%); padding: 1.4rem 1.6rem; border-radius: 1.15rem; color: #ffffff;">
        <div style="flex: 1; min-width: 220px;">
            <div style="display: inline-flex; align-items: center; gap: 0.4rem; padding: 0.35rem 0.8rem; border-radius: 999px; background: rgba(253, 224, 71, 0.25); color: #fef08a; font-size: 0.82rem; font-weight: 800; text-transform: uppercase; margin-bottom: 0.6rem; border: 1px solid rgba(253, 224, 71, 0.4);">
                <svg style="width: 1rem; height: 1rem;" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M11.3 1.046A1 1 0 0112 2v5h4a1 1 0 01.82 1.573l-7 10A1 1 0 018 18v-5H4a1 1 0 01-.82-1.573l7-10a1 1 0 011.12-.38z" clip-rule="evenodd" />
                </svg>
                {{ __('Abonnement Vendeur Pro') }}
            </div>
            <h4 style="margin: 0; font-size: 1.45rem; font-weight: 800; color: #ffffff; line-height: 1.25;">
                {{ __('Passez au statut Premium') }}
            </h4>
            <p style="margin: 0.45rem 0 0; font-size: 0.95rem; color: #ccfbf1; opacity: 0.95; line-height: 1.5;">
                {{ __('Publiez autant de produits que vous voulez sur Raaga sans aucune limite pendant toute l\'année.') }}
            </p>
        </div>

        <div style="background: rgba(255, 255, 255, 0.14); padding: 0.9rem 1.25rem; border-radius: 1rem; border: 1px solid rgba(255, 255, 255, 0.25); text-align: right; flex-shrink: 0;">
            <span style="display: block; font-size: 0.78rem; text-transform: uppercase; color: #99f6e4; font-weight: 700; letter-spacing: 0.05em;">{{ __('Tarif Annuel') }}</span>
            <div style="font-size: 1.85rem; font-weight: 900; color: #fde047; line-height: 1.1; margin-top: 0.25rem;">
                5 050 <span style="font-size: 0.9rem; font-weight: 700; color: #ffffff;">FCFA</span>
            </div>
            <span style="display: block; font-size: 0.78rem; color: #ccfbf1; margin-top: 0.2rem; font-weight: 500;">{{ __('Valable 12 mois complets') }}</span>
        </div>
    </div>

    {{-- Step 1 : Orange Money --}}
    <div class="pm-step orange-money" style="border: 1px solid #fed7aa; background: linear-gradient(135deg, #fffaf5 0%, #fff7ed 100%); border-radius: 1rem; padding: 1.2rem 1.35rem;">
        <div style="display: flex; justify-content: space-between; align-items: flex-start; gap: 1rem; flex-wrap: wrap;">
            <div style="display: flex; gap: 0.9rem; align-items: flex-start; flex: 1; min-width: 240px;">
                <div style="width: 2.1rem; height: 2.1rem; border-radius: 0.65rem; background: #ea580c; color: #ffffff; display: flex; align-items: center; justify-content: center; font-weight: 900; font-size: 0.95rem; flex-shrink: 0; margin-top: 0.1rem; box-shadow: 0 2px 8px rgba(234, 88, 12, 0.35);">
                    1
                </div>
                <div>
                    <div style="display: flex; align-items: center; gap: 0.5rem; flex-wrap: wrap;">
                        <span style="font-size: 0.8rem; font-weight: 800; text-transform: uppercase; color: #9a3412; background: #ffedd5; padding: 0.2rem 0.55rem; border-radius: 0.4rem; letter-spacing: 0.03em;">
                            Orange Money
                        </span>
                        <span style="font-size: 0.9rem; font-weight: 700; color: #1e293b;">{{ __('Paiement de 5 050 FCFA') }}</span>
                    </div>
                    <p style="margin: 0.4rem 0 0; font-size: 0.92rem; color: #475569; line-height: 1.5;">
                        {{ __('Effectuez le transfert vers le numéro de paiement officiel :') }}
                    </p>
                    <div style="margin-top: 0.6rem; display: inline-flex; align-items: center; background: #ffffff; padding: 0.45rem 0.95rem; border-radius: 0.65rem; border: 1.5px solid #fdba74; box-shadow: 0 2px 6px rgba(253, 186, 116, 0.25);">
                        <span style="font-size: 1.35rem; font-weight: 900; font-family: monospace; letter-spacing: 0.08em; color: #0f172a;">
                            {{ $paymentNumber }}
                        </span>
                    </div>
                </div>
            </div>

            <button type="button"
                    class="pm-copy-btn"
                    @click="copy('{{ str_replace(' ', '', $paymentNumber) }}', 'pay')"
                    style="flex-shrink: 0; padding: 0.55rem 1rem; font-size: 0.85rem;">
                <template x-if="!copiedPay">
                    <span style="display: inline-flex; align-items: center; gap: 0.4rem;">
                        <svg style="width: 1rem; height: 1rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                        </svg>
                        {{ __('Copier le numéro') }}
                    </span>
                </template>
                <template x-if="copiedPay">
                    <span style="display: inline-flex; align-items: center; gap: 0.4rem; color: #059669; font-weight: 800;">
                        <svg style="width: 1rem; height: 1rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                        </svg>
                        {{ __('Numéro copié !') }}
                    </span>
                </template>
            </button>
        </div>
    </div>

    {{-- Step 2 : Confirmation & Screenshot --}}
    <div class="pm-step confirmation" style="border: 1px solid #99f6e4; background: linear-gradient(135deg, #f0fdfa 0%, #ecfdf5 100%); border-radius: 1rem; padding: 1.2rem 1.35rem;">
        <div style="display: flex; justify-content: space-between; align-items: flex-start; gap: 1rem; flex-wrap: wrap;">
            <div style="display: flex; gap: 0.9rem; align-items: flex-start; flex: 1; min-width: 240px;">
                <div style="width: 2.1rem; height: 2.1rem; border-radius: 0.65rem; background: #0f766e; color: #ffffff; display: flex; align-items: center; justify-content: center; font-weight: 900; font-size: 0.95rem; flex-shrink: 0; margin-top: 0.1rem; box-shadow: 0 2px 8px rgba(15, 118, 110, 0.35);">
                    2
                </div>
                <div>
                    <div style="display: flex; align-items: center; gap: 0.5rem; flex-wrap: wrap;">
                        <span style="font-size: 0.8rem; font-weight: 800; text-transform: uppercase; color: #115e59; background: #ccfbf1; padding: 0.2rem 0.55rem; border-radius: 0.4rem; letter-spacing: 0.03em;">
                            {{ __('Confirmation') }}
                        </span>
                        <span style="font-size: 0.9rem; font-weight: 700; color: #1e293b;">{{ __('Envoi de la preuve') }}</span>
                    </div>
                    <p style="margin: 0.4rem 0 0; font-size: 0.92rem; color: #475569; line-height: 1.5;">
                        {{ __('Numéro WhatsApp / Appel pour activer votre compte :') }}
                    </p>
                    <div style="margin-top: 0.6rem; display: inline-flex; align-items: center; background: #ffffff; padding: 0.45rem 0.95rem; border-radius: 0.65rem; border: 1.5px solid #5eead4; box-shadow: 0 2px 6px rgba(94, 234, 212, 0.25);">
                        <span style="font-size: 1.35rem; font-weight: 900; font-family: monospace; letter-spacing: 0.08em; color: #0f172a;">
                            {{ $confirmationNumber }}
                        </span>
                    </div>
                </div>
            </div>

            <button type="button"
                    class="pm-copy-btn"
                    @click="copy('{{ str_replace(' ', '', $confirmationNumber) }}', 'confirm')"
                    style="flex-shrink: 0; padding: 0.55rem 1rem; font-size: 0.85rem;">
                <template x-if="!copiedConfirm">
                    <span style="display: inline-flex; align-items: center; gap: 0.4rem;">
                        <svg style="width: 1rem; height: 1rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                        </svg>
                        {{ __('Copier le numéro') }}
                    </span>
                </template>
                <template x-if="copiedConfirm">
                    <span style="display: inline-flex; align-items: center; gap: 0.4rem; color: #059669; font-weight: 800;">
                        <svg style="width: 1rem; height: 1rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                        </svg>
                        {{ __('Numéro copié !') }}
                    </span>
                </template>
            </button>
        </div>

        <div style="margin-top: 0.9rem; background: #ffffff; border-radius: 0.75rem; padding: 0.9rem 1rem; border: 1px solid #ccfbf1; font-size: 0.9rem; color: #334155; line-height: 1.6;">
            <strong style="color: #0f766e; font-size: 0.92rem;">{{ __('Consigne :') }}</strong>
            « {{ __('Après avoir effectué votre paiement, veuillez envoyer une capture d’écran de la transaction au') }} <strong style="color: #0f172a; font-size: 0.95rem;">74 65 09 24</strong> {{ __('afin que votre compte Premium puisse être activé.') }} »
        </div>
    </div>

    {{-- Access Buttons (Fully styled, high contrast and visible) --}}
    <div style="padding-top: 0.5rem; display: flex; flex-direction: column; gap: 0.75rem; margin-bottom: 0.75rem;">
        <a href="{{ $whatsappUrl }}" target="_blank" rel="noopener noreferrer"
           class="pm-btn-wa" style="padding: 1rem 1.5rem; font-size: 1rem; width: 100%; display: inline-flex; align-items: center; justify-content: center; gap: 0.6rem; text-decoration: none;">
            <svg style="width: 1.4rem; height: 1.4rem;" fill="currentColor" viewBox="0 0 24 24">
                <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/>
            </svg>
            <span>{{ __('Envoyer la capture sur WhatsApp (74 65 09 24)') }}</span>
        </a>

        <a href="tel:+226{{ str_replace(' ', '', $confirmationNumber) }}"
           class="pm-btn-phone" style="padding: 1rem 1.5rem; font-size: 1rem; width: 100%; display: inline-flex; align-items: center; justify-content: center; gap: 0.6rem; text-decoration: none; background: #f8fafc; border: 1.5px solid #cbd5e1; color: #1e293b; font-weight: 700; border-radius: 0.9rem; box-shadow: 0 2px 6px rgba(0,0,0,0.06);">
            <svg style="width: 1.25rem; height: 1.25rem; color: #0f766e;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
            </svg>
            <span>{{ __('Appeler le support (+226 74 65 09 24)') }}</span>
        </a>
    </div>
</div>
