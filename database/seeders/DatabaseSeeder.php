<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $admin = User::query()->updateOrCreate(
            ['email' => 'admin@vitrine.test'],
            [
                'name' => 'Admin',
                'first_name' => 'Super',
                'number_phone' => '0000000000',
                'locality' => 'Ouagadougou',
                'role' => 'admin',
                'password' => Hash::make('password'),
            ]
        );

        $seller = User::query()->updateOrCreate(
            ['email' => 'vendeur@vitrine.test'],
            [
                'name' => 'Diallo',
                'first_name' => 'Awa',
                'number_phone' => '0700000000',
                'locality' => 'Ouagadougou',
                'role' => 'user',
                'password' => Hash::make('password'),
            ]
        );

        $samples = [
            [
                'name' => 'Brosse a dents',
                'price' => 1500,
                'type_produits' => 'hygiene',
                'description' => 'Brosse à dents souple, idéale pour un usage quotidien.',
            ],
            [
                'name' => 'Patte alimentaire',
                'price' => 2500,
                'type_produits' => 'alimentaire',
                'description' => 'Pâte alimentaire de qualité, conditionnement familial.',
            ],
            [
                'name' => 'Paires de chaussures',
                'price' => 18000,
                'type_produits' => 'vetement',
                'description' => 'Chaussures confortables, style polyvalent.',
            ],
            [
                'name' => 'Costume',
                'price' => 45000,
                'type_produits' => 'vetement',
                'description' => 'Costume élégant, coupe soignée.',
            ],
            [
                'name' => 'Ordinateur',
                'price' => 250000,
                'type_produits' => 'electronique',
                'description' => 'Ordinateur performant pour le travail et les études.',
            ],
        ];

        foreach ($samples as $sample) {
            Product::query()->updateOrCreate(
                [
                    'user_id' => $seller->id,
                    'name' => $sample['name'],
                ],
                [
                    'price' => $sample['price'],
                    'type_produits' => $sample['type_produits'],
                    'description' => $sample['description'],
                    'photo' => null,
                    'status' => Product::STATUS_PUBLISHED,
                    'published_at' => now(),
                    'views_count' => 0,
                    'likes_count' => 0,
                ]
            );
        }
    }
}
