<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductLike extends Model
{
    protected $table = 'product_likes';

    protected $fillable = [
        'product_id',
        'visitor_key',
        'liked_at',
    ];

    protected function casts(): array
    {
        return [
            'liked_at' => 'datetime',
        ];
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
