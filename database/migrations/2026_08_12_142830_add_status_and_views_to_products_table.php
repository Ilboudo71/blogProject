<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            if (! Schema::hasColumn('products', 'status')) {
                $table->string('status')->default('draft');
            }

            if (! Schema::hasColumn('products', 'views_count')) {
                $table->unsignedBigInteger('views_count')->default(0);
            }

            if (! Schema::hasColumn('products', 'published_at')) {
                $table->timestamp('published_at')->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $columns = collect(['status', 'views_count', 'published_at'])
                ->filter(fn (string $column) => Schema::hasColumn('products', $column))
                ->all();

            if ($columns !== []) {
                $table->dropColumn($columns);
            }
        });
    }
};
