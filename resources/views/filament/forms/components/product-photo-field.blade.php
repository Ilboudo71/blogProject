<x-dynamic-component
    :component="$getFieldWrapperView()"
    :field="$field"
>
    @php
        $statePath = $getStatePath();
        $record = $getRecord();
        $productId = $record?->getKey();
        $currentUrl = \App\Models\Product::resolvePublicUrl($getState());
        $uploadUrl = route('products.photo.upload');
        $accept = collect(\App\Support\ProductPhotoService::allowedExtensions())
            ->map(fn (string $ext) => '.'.$ext)
            ->implode(',');
    @endphp

    <div
        wire:ignore.self
        class="raaga-photo-field"
        x-data="{
            previewUrl: @js($currentUrl),
            productId: @js($productId),
            uploadUrl: @js($uploadUrl),
            statePath: @js($statePath),
            uploading: false,
            error: null,
            success: null,
            openPicker() {
                if (this.uploading) return
                this.$refs.fileInput.click()
            },
            clearPhoto() {
                if (this.uploading || this.productId) return
                this.previewUrl = null
                this.success = null
                this.error = null
                $wire.set(this.statePath, null)
            },
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
                if (this.productId) body.append('product_id', this.productId)

                try {
                    const token = document.querySelector('meta[name=csrf-token]')?.content
                        || document.querySelector('input[name=_token]')?.value
                        || ''

                    const response = await fetch(this.uploadUrl, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': token,
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest',
                        },
                        credentials: 'same-origin',
                        body,
                    })

                    const data = await response.json().catch(() => ({}))
                    if (!response.ok) {
                        throw new Error(data?.errors?.photo?.[0] || data?.message || @js(__('Échec du chargement de la photo. Réessayez.')))
                    }

                    await $wire.set(this.statePath, data.path)
                    this.previewUrl = data.url + '?t=' + Date.now()
                    this.success = data.message || @js(__('Photo mise à jour.'))
                } catch (e) {
                    this.error = e?.message || @js(__('Échec du chargement de la photo. Réessayez.'))
                } finally {
                    this.uploading = false
                }
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

                <div class="raaga-photo-field__overlay" x-show="uploading" style="display:none;" x-cloak>
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
                        {{ __('Modifier la photo') }}
                    </button>

                    <button
                        type="button"
                        class="raaga-photo-field__btn raaga-photo-field__btn--ghost"
                        x-show="previewUrl && !productId"
                        style="display:none;"
                        x-cloak
                        @click="clearPhoto()"
                        :disabled="uploading"
                    >
                        {{ __('Retirer') }}
                    </button>
                </div>

                <p class="raaga-photo-field__hint">
                    {{ __('JPG, PNG, WEBP, GIF… max. 20 Mo. Cliquez sur Modifier la photo pour remplacer l’image immédiatement.') }}
                </p>

                <p class="raaga-photo-field__error" x-show="error" x-text="error" style="display:none;" x-cloak></p>
                <p class="raaga-photo-field__success" x-show="success" x-text="success" style="display:none;" x-cloak></p>
            </div>
        </div>

        <input
            x-ref="fileInput"
            type="file"
            class="raaga-photo-field__input"
            accept="image/*,{{ $accept }}"
            @change="onFileChange($event)"
        >
    </div>
</x-dynamic-component>
