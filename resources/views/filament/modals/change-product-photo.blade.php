@php
    /** @var \App\Models\Product $product */
    $uploadUrl = route('products.photo.upload');
    $currentUrl = $product->photo_url;
    $accept = collect(\App\Support\ProductPhotoService::allowedExtensions())
        ->map(fn (string $ext) => '.'.$ext)
        ->implode(',');
@endphp

<div
    class="raaga-photo-field"
    x-data="{
        previewUrl: @js($currentUrl),
        uploading: false,
        error: null,
        success: null,
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
            body.append('product_id', @js($product->id))

            try {
                const token = document.querySelector('meta[name=csrf-token]')?.content
                    || document.querySelector('input[name=_token]')?.value
                    || ''

                const response = await fetch(@js($uploadUrl), {
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

                this.previewUrl = data.url + '?t=' + Date.now()
                this.success = data.message || @js(__('Photo mise à jour.'))
                setTimeout(() => window.location.reload(), 600)
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
            <div class="raaga-photo-field__overlay" x-show="uploading" x-cloak>
                <span class="raaga-photo-field__spinner" aria-hidden="true"></span>
                <span>{{ __('Envoi…') }}</span>
            </div>
        </div>

        <div class="raaga-photo-field__actions">
            <div class="raaga-photo-field__buttons">
                <label class="raaga-photo-field__btn" style="display:inline-flex;align-items:center;justify-content:center;cursor:pointer;">
                    <span x-text="uploading ? @js(__('Envoi…')) : @js(__('Choisir une nouvelle photo'))"></span>
                    <input type="file" accept="image/*,{{ $accept }}" style="display:none" @change="onFileChange($event)" :disabled="uploading">
                </label>
            </div>
            <p class="raaga-photo-field__hint">{{ __('La photo est enregistrée immédiatement après sélection.') }}</p>
            <p class="raaga-photo-field__error" x-show="error" x-text="error" x-cloak></p>
            <p class="raaga-photo-field__success" x-show="success" x-text="success" x-cloak></p>
        </div>
    </div>
</div>
