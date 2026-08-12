<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product_likes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
            $table->string('visitor_key', 64);
            $table->timestamp('liked_at')->useCurrent();
            $table->timestamps();

            $table->unique(['product_id', 'visitor_key']);
            $table->index('liked_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_likes');
    }
};
