@php
    $likedProductIds = $likedProductIds ?? collect();
    $isLiked = $isLiked ?? $likedProductIds->contains($product->id);
@endphp

<button
    type="button"
    class="like-btn {{ $isLiked ? 'is-liked' : '' }}"
    data-like-button
    data-product-id="{{ $product->id }}"
    data-like-url="{{ route('marketplace.like', $product) }}"
    aria-pressed="{{ $isLiked ? 'true' : 'false' }}"
    aria-label="{{ $isLiked ? 'Retirer le like' : 'Aimer ce produit' }}"
>
    <svg class="like-btn-icon" viewBox="0 0 24 24" aria-hidden="true">
        <path d="M12 21s-6.7-4.35-9.33-7.4C.7 11.3 1.1 7.8 3.6 6.05 5.7 4.55 8.4 5 12 8.1c3.6-3.1 6.3-3.55 8.4-2.05 2.5 1.75 2.9 5.25.93 7.55C18.7 16.65 12 21 12 21z"/>
    </svg>
    <span data-like-count>{{ number_format((int) $product->likes_count, 0, ',', ' ') }}</span>
</button>
