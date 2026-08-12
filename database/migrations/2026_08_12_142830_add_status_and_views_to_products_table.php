<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->string('status')->default('draft')->after('type_produits');
            $table->unsignedBigInteger('views_count')->default(0)->after('status');
            $table->timestamp('published_at')->nullable()->after('views_count');
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['status', 'views_count', 'published_at']);
        });
    }
};
