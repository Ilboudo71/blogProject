<x-dynamic-component
    :component="$getFieldWrapperView()"
    :field="$field"
>
    @php
        $statePath = $getStatePath();
        $record = $getRecord();
        $productId = $record?->getKey();
        $currentUrl = $record?->photo_url ?: \App\Models\Product::resolvePublicUrl($getState());
        $uploadUrl = route('products.photo.upload');
        $accept = collect(\App\Support\ProductPhotoService::allowedExtensions())
            ->map(fn (string $ext) => '.'.$ext)
            ->implode(',');
        $inputId = 'raaga-photo-'.md5($statePath);
    @endphp

    <div
        class="raaga-photo-field"
        style="width:100%;"
        x-data="{
            previewUrl: @js($currentUrl),
            productId: @js($productId),
            uploadUrl: @js($uploadUrl),
            statePath: @js($statePath),
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

                // Aperçu local immédiat
                this.previewUrl = URL.createObjectURL(file)
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
                    this.previewUrl = data.url + (data.url.includes('?') ? '&' : '?') + 't=' + Date.now()
                    this.success = data.message || @js(__('Photo mise à jour.'))
                } catch (e) {
                    this.error = e?.message || @js(__('Échec du chargement de la photo. Réessayez.'))
                } finally {
                    this.uploading = false
                }
            }
        }"
    >
        <div class="raaga-photo-field__card" style="display:flex;flex-wrap:wrap;gap:1rem;align-items:center;padding:0.9rem;border:1px solid rgba(15,23,42,0.12);border-radius:1rem;background:#f8fafc;">
            <div class="raaga-photo-field__preview" style="position:relative;width:150px;height:150px;border-radius:0.85rem;overflow:hidden;background:#e2e8f0;flex:0 0 auto;">
                <img
                    x-show="previewUrl"
                    x-bind:src="previewUrl"
                    alt="{{ __('Photo du produit') }}"
                    class="raaga-photo-field__img"
                    style="width:100%;height:100%;object-fit:cover;display:block;"
                >
                <div
                    x-show="!previewUrl"
                    class="raaga-photo-field__placeholder"
                    style="width:100%;height:100%;display:grid;place-items:center;color:#64748b;font-size:0.9rem;font-weight:700;text-align:center;padding:0.5rem;"
                >
                    {{ __('Aucune photo') }}
                </div>
                <div
                    class="raaga-photo-field__overlay"
                    x-show="uploading"
                    style="display:none;position:absolute;inset:0;display:none;align-items:center;justify-content:center;flex-direction:column;gap:0.35rem;background:rgba(15,23,42,0.55);color:#fff;font-size:0.85rem;font-weight:700;"
                    x-bind:style="uploading ? 'display:flex;position:absolute;inset:0;align-items:center;justify-content:center;flex-direction:column;gap:0.35rem;background:rgba(15,23,42,0.55);color:#fff;font-size:0.85rem;font-weight:700;' : 'display:none'"
                >
                    <span>{{ __('Envoi…') }}</span>
                </div>
            </div>

            <div class="raaga-photo-field__actions" style="flex:1 1 200px;min-width:0;">
                <label
                    for="{{ $inputId }}"
                    class="raaga-photo-field__btn"
                    style="display:inline-flex;align-items:center;justify-content:center;appearance:none;border:0;border-radius:0.7rem;background:#0f766e;color:#fff;font-weight:800;font-size:1rem;padding:0.7rem 1.1rem;cursor:pointer;"
                >
                    <span x-text="uploading ? @js(__('Envoi…')) : (previewUrl ? @js(__('Modifier')) : @js(__('Ajouter une photo')))"></span>
                </label>

                <input
                    id="{{ $inputId }}"
                    type="file"
                    accept="image/*,{{ $accept }}"
                    style="position:absolute;left:-9999px;width:1px;height:1px;opacity:0;"
                    @change="onFileChange($event)"
                    :disabled="uploading"
                >

                <p class="raaga-photo-field__hint" style="margin:0.55rem 0 0;color:#64748b;font-size:0.9rem;line-height:1.4;">
                    {{ __('JPG, PNG, WEBP, GIF… max. 20 Mo. Cliquez sur Modifier pour remplacer la photo immédiatement.') }}
                </p>

                <p class="raaga-photo-field__error" x-show="error" x-text="error" style="display:none;margin:0.4rem 0 0;color:#b91c1c;font-size:0.9rem;font-weight:700;"></p>
                <p class="raaga-photo-field__success" x-show="success" x-text="success" style="display:none;margin:0.4rem 0 0;color:#047857;font-size:0.9rem;font-weight:700;"></p>
            </div>
        </div>
    </div>
</x-dynamic-component>
