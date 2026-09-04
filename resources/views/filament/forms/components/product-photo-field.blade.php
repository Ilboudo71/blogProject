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
        $inputId = 'raaga-photo-'.md5($statePath.(string) $productId);
    @endphp

    <div
        class="raaga-photo-field"
        style="width:100%; margin-top:0.35rem;"
        x-data="{
            previewUrl: @js($currentUrl),
            productId: @js($productId),
            uploadUrl: @js($uploadUrl),
            statePath: @js($statePath),
            uploading: false,
            error: null,
            success: null,
            tooLargeMsg: @js(__('Image trop volumineuse (max. 20 Mo).')),
            failMsg: @js(__('Echec du chargement de la photo. Reessayez.')),
            updatedMsg: @js(__('Photo mise a jour.')),
            async onFileChange(event) {
                const file = event.target.files && event.target.files[0] ? event.target.files[0] : null;
                event.target.value = '';
                if (!file) {
                    return;
                }

                this.error = null;
                this.success = null;

                if (file.size > 20 * 1024 * 1024) {
                    this.error = this.tooLargeMsg;
                    return;
                }

                this.previewUrl = URL.createObjectURL(file);
                this.uploading = true;

                const body = new FormData();
                body.append('photo', file);
                if (this.productId) {
                    body.append('product_id', this.productId);
                }

                try {
                    const meta = document.querySelector('meta[name=csrf-token]');
                    const tokenInput = document.querySelector('input[name=_token]');
                    const token = (meta && meta.content) ? meta.content : ((tokenInput && tokenInput.value) ? tokenInput.value : '');

                    const response = await fetch(this.uploadUrl, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': token,
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest',
                        },
                        credentials: 'same-origin',
                        body: body,
                    });

                    const data = await response.json().catch(function () { return {}; });
                    if (!response.ok) {
                        const msg = (data.errors && data.errors.photo && data.errors.photo[0])
                            ? data.errors.photo[0]
                            : (data.message || this.failMsg);
                        throw new Error(msg);
                    }

                    await $wire.set(this.statePath, data.path);
                    const sep = (data.url && data.url.indexOf('?') >= 0) ? '&' : '?';
                    this.previewUrl = data.url + sep + 't=' + Date.now();
                    this.success = data.message || this.updatedMsg;
                } catch (e) {
                    this.error = (e && e.message) ? e.message : this.failMsg;
                } finally {
                    this.uploading = false;
                }
            }
        }"
    >
        <div style="display:flex; flex-wrap:wrap; gap:1rem; align-items:center; padding:1rem; border:1px solid #cbd5e1; border-radius:1rem; background:#f8fafc;">
            <div style="position:relative; width:160px; height:160px; border-radius:0.9rem; overflow:hidden; background:#e2e8f0; flex:0 0 auto; border:1px solid #cbd5e1;">
                <template x-if="previewUrl">
                    <img :src="previewUrl" alt="{{ __('Photo du produit') }}" style="width:100%; height:100%; object-fit:cover; display:block;">
                </template>
                <template x-if="!previewUrl">
                    <div style="width:100%; height:100%; display:grid; place-items:center; color:#475569; font-size:1rem; font-weight:700; text-align:center; padding:0.5rem;">
                        {{ __('Aucune photo') }}
                    </div>
                </template>
                <div x-show="uploading" style="position:absolute; inset:0; display:flex; align-items:center; justify-content:center; background:rgba(15,23,42,0.55); color:#fff; font-weight:700;">
                    {{ __('Envoi...') }}
                </div>
            </div>

            <div style="flex:1 1 220px; min-width:0;">
                <label
                    for="{{ $inputId }}"
                    style="display:inline-flex; align-items:center; justify-content:center; min-height:2.75rem; padding:0.75rem 1.2rem; border-radius:0.75rem; background:#0f766e; color:#fff; font-weight:800; font-size:1.1rem; cursor:pointer; border:0;"
                >
                    <span x-text="uploading ? @js(__('Envoi...')) : (previewUrl ? @js(__('Modifier')) : @js(__('Ajouter une photo')))"></span>
                </label>

                <input
                    id="{{ $inputId }}"
                    type="file"
                    accept="image/*,{{ $accept }}"
                    style="position:absolute; width:1px; height:1px; padding:0; margin:-1px; overflow:hidden; clip:rect(0,0,0,0); border:0;"
                    @change="onFileChange($event)"
                    :disabled="uploading"
                >

                <p style="margin:0.65rem 0 0; color:#475569; font-size:1rem; line-height:1.45;">
                    {{ __('Formats: JPG, PNG, WEBP, GIF. Max 20 Mo. Cliquez Modifier pour changer la photo.') }}
                </p>

                <p x-show="error" x-text="error" style="margin:0.45rem 0 0; color:#b91c1c; font-size:1rem; font-weight:700;"></p>
                <p x-show="success" x-text="success" style="margin:0.45rem 0 0; color:#047857; font-size:1rem; font-weight:700;"></p>
            </div>
        </div>
    </div>
</x-dynamic-component>
