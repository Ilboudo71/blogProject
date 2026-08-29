<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Filament\Models\Contracts\FilamentUser;
use Filament\Models\Contracts\HasAvatar;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;

class User extends Authenticatable implements FilamentUser, HasAvatar
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    public const PREMIUM_PRICE_FCFA = 5050;

    public const PREMIUM_PAYMENT_NUMBER = '74 31 61 53';

    public const PREMIUM_CONFIRMATION_NUMBER = '74 65 09 24';

    public const FREE_PRODUCT_LIMIT = 1;

    protected $table = 'users';

    protected $fillable = [
        'name',
        'photo',
        'first_name',
        'number_phone',
        'locality',
        'email',
        'role',
        'is_premium',
        'premium_activated_at',
        'premium_expires_at',
        'premium_payment_ref',
        'password',
        'email_verified_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $appends = [
        'full_name',
        'photo_url',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'is_premium' => 'boolean',
            'premium_activated_at' => 'datetime',
            'premium_expires_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function canAccessPanel(Panel $panel): bool
    {
        return match ($panel->getId()) {
            'admin' => $this->isAdmin(),
            'user' => $this->isSeller(),
            default => false,
        };
    }

    public function getFilamentAvatarUrl(): ?string
    {
        return $this->photo_url;
    }

    public function getPhotoUrlAttribute(): ?string
    {
        return Product::resolvePublicUrl($this->photo);
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isSeller(): bool
    {
        return $this->role === 'user';
    }

    public function panelHomeUrl(): string
    {
        return $this->isAdmin()
            ? url('/admin')
            : url('/user');
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    public function getFullNameAttribute(): string
    {
        return trim("{$this->first_name} {$this->name}");
    }

    public function getFormattedPhoneAttribute(): ?string
    {
        $phone = trim((string) ($this->number_phone ?? ''));

        return $phone !== '' ? $phone : null;
    }

    public function whatsappNumber(): ?string
    {
        $digits = preg_replace('/\D+/', '', (string) ($this->number_phone ?? '')) ?: null;

        if (! $digits) {
            return null;
        }

        // Numéro local (ex. 07… / 70…) → indicatif Burkina Faso par défaut.
        if (str_starts_with($digits, '0') && strlen($digits) >= 8) {
            return '226'.substr($digits, 1);
        }

        if (strlen($digits) <= 9) {
            return '226'.$digits;
        }

        return $digits;
    }

    public function isPremium(): bool
    {
        if (! (bool) $this->is_premium) {
            return false;
        }

        if ($this->premium_expires_at === null) {
            return true;
        }

        return $this->premium_expires_at->isFuture();
    }

    public function isPremiumExpired(): bool
    {
        return (bool) $this->is_premium && $this->premium_expires_at !== null && $this->premium_expires_at->isPast();
    }

    public function activatePremium(?\DateTimeInterface $expiresAt = null, ?string $paymentRef = null): void
    {
        $this->update([
            'is_premium' => true,
            'premium_activated_at' => now(),
            'premium_expires_at' => $expiresAt ?? now()->addYear(),
            'premium_payment_ref' => $paymentRef ?? $this->premium_payment_ref,
        ]);
    }

    public function deactivatePremium(): void
    {
        $this->update([
            'is_premium' => false,
        ]);
    }

    public function publishedProductsCount(): int
    {
        return $this->products()->published()->count();
    }

    public function canPublishMoreProducts(): bool
    {
        if ($this->isAdmin() || $this->isPremium()) {
            return true;
        }

        return $this->publishedProductsCount() < self::FREE_PRODUCT_LIMIT;
    }

    public function canCreateProduct(): bool
    {
        if ($this->isAdmin() || $this->isPremium()) {
            return true;
        }

        return $this->products()->count() < self::FREE_PRODUCT_LIMIT;
    }

    public static function premiumWhatsappConfirmationUrl(): string
    {
        $digits = '226'.preg_replace('/\D+/', '', self::PREMIUM_CONFIRMATION_NUMBER);
        $text = rawurlencode("Bonjour, j'ai effectué le paiement de 5 050 FCFA par Orange Money pour l'abonnement Premium Raaga. Voici ma preuve de paiement :");

        return "https://wa.me/{$digits}?text={$text}";
    }

    public function mailtoInquiryUrl(string $productName): ?string
    {
        if (! filled($this->email)) {
            return null;
        }

        $subject = rawurlencode("Intérêt pour « {$productName} » sur Raaga");
        $body = rawurlencode(
            "Bonjour {$this->full_name},\n\n".
            "Je suis intéressé(e) par votre produit « {$productName} » publié sur Raaga.\n\n".
            "Pouvez-vous me donner plus d'informations ?\n\n".
            "Cordialement,"
        );

        return "mailto:{$this->email}?subject={$subject}&body={$body}";
    }

    public function whatsappInquiryUrl(string $productName): ?string
    {
        $number = $this->whatsappNumber();

        if (! $number) {
            return null;
        }

        $text = rawurlencode(
            "Bonjour {$this->full_name}, je suis intéressé(e) par votre produit « {$productName} » sur Raaga."
        );

        return "https://wa.me/{$number}?text={$text}";
    }
}
