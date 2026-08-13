<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            if (! Schema::hasColumn('products', 'type_produits')) {
                $table->string('type_produits')->nullable();
            }

            if (! Schema::hasColumn('products', 'photo')) {
                $table->string('photo')->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $columns = collect(['type_produits', 'photo'])
                ->filter(fn (string $column) => Schema::hasColumn('products', $column))
                ->all();

            if ($columns !== []) {
                $table->dropColumn($columns);
            }
        });
    }
};
