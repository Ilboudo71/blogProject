<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $table = 'products';
    protected $fillable = [
        'name',
        'price',
        'user_id',
        'descripton',
        'type_produits',
        'photo'
    ];


    // Les relations entre les produits et les utilisateurs
            public function user()
        {
            return $this->belongsTo(User::class);
        }

}
