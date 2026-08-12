@php
    $seller = $product->user;
    $mailto = $seller?->mailtoInquiryUrl($product->name);
    $whatsapp = $seller?->whatsappInquiryUrl($product->name);
@endphp

<article class="product-card">
    <a href="{{ route('marketplace.show', $product) }}" class="product-card-media">
        @if ($product->photo_url)
            <img src="{{ $product->photo_url }}" alt="{{ $product->name }}" loading="lazy">
        @else
            <div class="product-fallback">{{ strtoupper(substr($product->name, 0, 1)) }}</div>
        @endif
        <span class="product-card-badge">{{ $product->type_label }}</span>
    </a>

    <div class="product-card-body">
        <a href="{{ route('marketplace.show', $product) }}" class="product-card-link">
            <h3>{{ $product->name }}</h3>
        </a>

        <div class="product-meta">
            <strong>{{ number_format((float) $product->price, 0, ',', ' ') }} FCFA</strong>
            <span>{{ number_format($product->views_count, 0, ',', ' ') }} vues</span>
        </div>

        <div class="product-card-actions">
            <div class="product-like-row">
                @include('marketplace.partials.like-button', [
                    'product' => $product,
                    'likedProductIds' => $likedProductIds ?? collect(),
                ])
            </div>
            <a href="{{ route('marketplace.show', $product) }}" class="product-more-link">Voir plus</a>
        </div>

        @if ($seller)
            <div class="product-seller-block">
                <div>
                    <p class="product-seller">{{ $seller->full_name ?: $seller->name }}</p>
                    @if ($seller->locality)
                        <p class="product-locality">{{ $seller->locality }}</p>
                    @endif
                </div>

                <div class="contact-mini">
                    @if ($seller->formatted_phone)
                        <p class="contact-mini-line">{{ $seller->formatted_phone }}</p>
                    @endif
                    @if ($seller->email)
                        <p class="contact-mini-line">{{ $seller->email }}</p>
                    @endif
                </div>

                <div class="contact-actions">
                    @if ($mailto)
                        <a href="{{ $mailto }}" class="btn-icon btn-mail" title="Écrire un e-mail" aria-label="Écrire un e-mail au vendeur">
                            <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M4 6h16a1 1 0 0 1 1 1v10a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V7a1 1 0 0 1 1-1Zm8 6.5L4.75 7.5h14.5L12 12.5Z" fill="currentColor"/></svg>
                            <span>Mail</span>
                        </a>
                    @endif
                    @if ($whatsapp)
                        <a href="{{ $whatsapp }}" target="_blank" rel="noopener noreferrer" class="btn-icon btn-whatsapp" title="Écrire sur WhatsApp" aria-label="Écrire au vendeur sur WhatsApp">
                            <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12.04 2C6.58 2 2.15 6.4 2.15 11.82c0 2.08.6 4.02 1.65 5.66L2 22l4.7-1.73a9.86 9.86 0 0 0 5.34 1.55h.01c5.46 0 9.89-4.4 9.89-9.82C21.94 6.4 17.5 2 12.04 2Zm5.74 14.05c-.24.67-1.4 1.23-1.93 1.31-.5.07-1.13.1-1.82-.11-.42-.13-.96-.31-1.65-.61-2.9-1.26-4.79-4.2-4.93-4.39-.14-.2-1.15-1.53-1.15-2.92 0-1.39.73-2.07.99-2.35.26-.28.57-.35.76-.35h.55c.18 0 .42-.07.66.5.24.58.82 2 .89 2.15.07.15.12.32.02.52-.1.2-.15.32-.3.5-.15.17-.31.39-.44.52-.15.15-.3.31-.13.6.17.3.76 1.25 1.63 2.03 1.12 1 2.07 1.31 2.37 1.46.3.15.47.12.65-.07.17-.2.75-.87.95-1.17.2-.3.4-.25.67-.15.27.1 1.72.81 2.02.96.3.15.5.22.57.35.08.12.08.72-.16 1.39Z" fill="currentColor"/></svg>
                            <span>WhatsApp</span>
                        </a>
                    @endif
                </div>
            </div>
        @endif
    </div>
</article>
