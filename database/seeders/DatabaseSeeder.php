<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        User::query()->updateOrCreate(
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

        User::query()->updateOrCreate(
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
    }
}
