<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class Product extends Model
{
    public const STATUS_DRAFT = 'draft';

    public const STATUS_PUBLISHED = 'published';

    protected $table = 'products';

    protected $fillable = [
        'name',
        'price',
        'user_id',
        'description',
        'type_produits',
        'photo',
        'photo_mime',
        'status',
        'views_count',
        'likes_count',
        'published_at',
    ];

    protected $hidden = [
        'photo_data',
    ];

    protected $appends = [
        'photo_url',
        'type_label',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'views_count' => 'integer',
            'likes_count' => 'integer',
            'published_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function likes(): HasMany
    {
        return $this->hasMany(ProductLike::class);
    }

    public function views(): HasMany
    {
        return $this->hasMany(ProductView::class);
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_PUBLISHED);
    }

    public function isPublished(): bool
    {
        return $this->status === self::STATUS_PUBLISHED;
    }

    public function publish(): void
    {
        $this->forceFill([
            'status' => self::STATUS_PUBLISHED,
            'published_at' => $this->published_at ?? Carbon::now(),
        ])->save();
    }

    public function unpublish(): void
    {
        $this->forceFill([
            'status' => self::STATUS_DRAFT,
        ])->save();
    }

    /**
     * Enregistre une vue pour un visiteur, au plus une fois toutes les 24 heures.
     */
    public function recordView(string $visitorKey): bool
    {
        return (bool) DB::transaction(function () use ($visitorKey) {
            $recentView = ProductView::query()
                ->where('product_id', $this->id)
                ->where('visitor_key', $visitorKey)
                ->where('viewed_at', '>=', now()->subDay())
                ->lockForUpdate()
                ->exists();

            if ($recentView) {
                return false;
            }

            ProductView::query()->create([
                'product_id' => $this->id,
                'visitor_key' => $visitorKey,
                'viewed_at' => now(),
            ]);

            $this->increment('views_count');
            $this->refresh();

            return true;
        });
    }

    public static function typeLabels(): array
    {
        return [
            'hygiene' => __('Hygiène'),
            'alimentaire' => __('Alimentaire'),
            'electronique' => __('Électronique'),
            'vetement' => __('Vêtements'),
            'autres' => __('Autres'),
        ];
    }

    public function getTypeLabelAttribute(): string
    {
        return self::typeLabels()[$this->type_produits] ?? (string) $this->type_produits;
    }

    public function getPhotoUrlAttribute(): ?string
    {
        if ($this->id && (filled($this->photo) || filled($this->photo_data))) {
            $version = $this->updated_at?->timestamp ?? time();

            return url('/media/products/'.$this->id).'?v='.$version;
        }

        return self::resolvePublicUrl($this->photo);
    }

    public static function resolvePublicUrl(mixed $path): ?string
    {
        if (is_array($path)) {
            $path = $path[0] ?? null;
        }

        if (is_string($path)) {
            $trimmed = trim($path);

            // Filament peut parfois stocker un JSON ["products/xxx.jpg"]
            if ($trimmed !== '' && ($trimmed[0] === '[' || $trimmed[0] === '{')) {
                $decoded = json_decode($trimmed, true);
                if (is_array($decoded)) {
                    $path = $decoded[0] ?? null;
                }
            } else {
                $path = $trimmed;
            }
        }

        $path = is_string($path) ? trim($path) : '';

        if ($path === '') {
            return null;
        }

        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://') || str_starts_with($path, 'data:')) {
            return $path;
        }

        $path = ltrim(str_replace('\\', '/', $path), '/');

        if (str_starts_with($path, 'storage/')) {
            $path = substr($path, strlen('storage/'));
        }

        if (str_starts_with($path, 'public/')) {
            $path = substr($path, strlen('public/'));
        }

        // URL relative : fonctionne même si APP_URL est incorrect sur Render.
        return '/storage/'.$path;
    }
}
