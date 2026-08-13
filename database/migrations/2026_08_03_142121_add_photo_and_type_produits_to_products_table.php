<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('products', 'type_produits')) {
            Schema::table('products', function (Blueprint $table) {
                $table->string('type_produits')->nullable();
            });
        }

        if (! Schema::hasColumn('products', 'photo')) {
            Schema::table('products', function (Blueprint $table) {
                $table->string('photo');
            });
        }
    }

    public function down(): void
    {
        $columns = collect(['type_produits', 'photo'])
            ->filter(fn (string $column) => Schema::hasColumn('products', $column))
            ->values()
            ->all();

        if ($columns !== []) {
            Schema::table('products', function (Blueprint $table) use ($columns) {
                $table->dropColumn($columns);
            });
        }
    }
};
