@php
    $statePath = $getStatePath();
    $productId = $productId ?? null;
    $currentUrl = \App\Models\Product::resolvePublicUrl($getState());
    $uploadUrl = route('products.photo.upload');
    $accept = collect(\App\Support\ProductPhotoService::allowedExtensions())
        ->map(fn (string $ext) => '.'.$ext)
        ->implode(',');
@endphp

<div
    wire:ignore
    class="raaga-photo-field"
    x-data="{
        state: $wire.$entangle('{{ $statePath }}'),
        previewUrl: @js($currentUrl),
        uploading: false,
        error: null,
        success: null,
        productId: @js($productId),
        uploadUrl: @js($uploadUrl),
        async onFileChange(event) {
            const file = event.target.files?.[0]
            event.target.value = ''

            if (!file) return

            this.error = null
            this.success = null

            if (file.size > 20 * 1024 * 1024) {
                this.error = @js(__('L’image ne doit pas dépasser 20 Mo.'))
                return
            }

            this.uploading = true

            const body = new FormData()
            body.append('photo', file)
            if (this.productId) {
                body.append('product_id', this.productId)
            }

            try {
                const response = await fetch(this.uploadUrl, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]')?.content
                            || document.querySelector('input[name=_token]')?.value
                            || '',
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                    },
                    credentials: 'same-origin',
                    body,
                })

                const data = await response.json().catch(() => ({}))

                if (!response.ok) {
                    const message = data?.message
                        || data?.errors?.photo?.[0]
                        || @js(__('Échec du chargement de la photo. Réessayez.'))
                    throw new Error(message)
                }

                this.state = data.path
                if (window.Livewire) {
                    $wire.set('{{ $statePath }}', data.path)
                }
                this.previewUrl = data.url + '?t=' + Date.now()
                this.success = data.message || @js(__('Photo mise à jour.'))
            } catch (e) {
                this.error = e?.message || @js(__('Échec du chargement de la photo. Réessayez.'))
            } finally {
                this.uploading = false
            }
        },
        openPicker() {
            if (this.uploading) return
            this.$refs.input.click()
        },
        clearPhoto() {
            if (this.uploading || this.productId) return
            this.state = null
            this.previewUrl = null
            this.success = null
            this.error = null
        }
    }"
>
    <div class="raaga-photo-field__card">
        <div class="raaga-photo-field__preview">
            <template x-if="previewUrl">
                <img :src="previewUrl" alt="{{ __('Photo du produit') }}" class="raaga-photo-field__img">
            </template>
            <template x-if="!previewUrl">
                <div class="raaga-photo-field__placeholder">
                    <span>{{ __('Aucune photo') }}</span>
                </div>
            </template>

            <div class="raaga-photo-field__overlay" x-show="uploading" x-cloak>
                <span class="raaga-photo-field__spinner" aria-hidden="true"></span>
                <span>{{ __('Envoi…') }}</span>
            </div>
        </div>

        <div class="raaga-photo-field__actions">
            <div class="raaga-photo-field__buttons">
                <button
                    type="button"
                    class="raaga-photo-field__btn"
                    @click="openPicker()"
                    :disabled="uploading"
                >
                    <span x-text="previewUrl ? @js(__('Modifier')) : @js(__('Ajouter une photo'))"></span>
                </button>

                <button
                    type="button"
                    class="raaga-photo-field__btn raaga-photo-field__btn--ghost"
                    x-show="previewUrl && !productId"
                    x-cloak
                    @click="clearPhoto()"
                    :disabled="uploading"
                >
                    {{ __('Retirer') }}
                </button>
            </div>

            <p class="raaga-photo-field__hint">
                {{ __('JPG, PNG, WEBP, GIF… max. 20 Mo. Cliquez sur Modifier pour remplacer la photo immédiatement.') }}
            </p>

            <p class="raaga-photo-field__error" x-show="error" x-text="error" x-cloak></p>
            <p class="raaga-photo-field__success" x-show="success" x-text="success" x-cloak></p>
        </div>
    </div>

    <input
        x-ref="input"
        type="file"
        class="raaga-photo-field__input"
        accept="image/*,{{ $accept }}"
        @change="onFileChange"
    >
</div>
