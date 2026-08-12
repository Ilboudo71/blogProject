<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

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
        'status',
        'views_count',
        'likes_count',
        'published_at',
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
            'hygiene' => 'Hygiène',
            'alimentaire' => 'Alimentaire',
            'electronique' => 'Électronique',
            'vetement' => 'Vêtements',
            'autres' => 'Autres',
        ];
    }

    public function getTypeLabelAttribute(): string
    {
        return self::typeLabels()[$this->type_produits] ?? (string) $this->type_produits;
    }

    public function getPhotoUrlAttribute(): ?string
    {
        return self::resolvePublicUrl($this->photo);
    }

    public static function resolvePublicUrl(mixed $path): ?string
    {
        if (is_array($path)) {
            $path = $path[0] ?? null;
        }

        $path = is_string($path) ? trim($path) : '';

        if ($path === '') {
            return null;
        }

        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://') || str_starts_with($path, 'data:')) {
            return $path;
        }

        $path = ltrim($path, '/');

        if (str_starts_with($path, 'storage/')) {
            $path = substr($path, strlen('storage/'));
        }

        return Storage::disk('public')->url($path);
    }
}
