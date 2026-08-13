<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('products', 'status')) {
            Schema::table('products', function (Blueprint $table) {
                $table->string('status')->default('draft');
            });
        }

        if (! Schema::hasColumn('products', 'views_count')) {
            Schema::table('products', function (Blueprint $table) {
                $table->unsignedBigInteger('views_count')->default(0);
            });
        }

        if (! Schema::hasColumn('products', 'published_at')) {
            Schema::table('products', function (Blueprint $table) {
                $table->timestamp('published_at')->nullable();
            });
        }
    }

    public function down(): void
    {
        $columns = collect(['status', 'views_count', 'published_at'])
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
