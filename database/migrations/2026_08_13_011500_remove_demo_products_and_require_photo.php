<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('products', 'photo')) {
            return;
        }

        // Remove demo/test products created without photos.
        DB::table('products')->whereNull('photo')->delete();
        DB::table('products')->whereIn('name', [
            'Brosse a dents',
            'Patte alimentaire',
            'Paires de chaussures',
            'Costume',
            'Ordinateur',
        ])->delete();

        $driver = Schema::getConnection()->getDriverName();

        if ($driver === 'pgsql') {
            DB::statement('ALTER TABLE products ALTER COLUMN photo SET NOT NULL');
        } elseif ($driver === 'mysql') {
            DB::statement('ALTER TABLE products MODIFY photo VARCHAR(255) NOT NULL');
        }
    }

    public function down(): void
    {
        if (! Schema::hasColumn('products', 'photo')) {
            return;
        }

        $driver = Schema::getConnection()->getDriverName();

        if ($driver === 'pgsql') {
            DB::statement('ALTER TABLE products ALTER COLUMN photo DROP NOT NULL');
        } elseif ($driver === 'mysql') {
            DB::statement('ALTER TABLE products MODIFY photo VARCHAR(255) NULL');
        }
    }
};
