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

        $driver = Schema::getConnection()->getDriverName();

        if ($driver === 'pgsql') {
            DB::statement('ALTER TABLE products ALTER COLUMN photo DROP NOT NULL');
        } elseif ($driver === 'mysql') {
            DB::statement('ALTER TABLE products MODIFY photo VARCHAR(255) NULL');
        }
    }

    public function down(): void
    {
        // Keep photo nullable; reversing would break rows without photos.
    }
};
