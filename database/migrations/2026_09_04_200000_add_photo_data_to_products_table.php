<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            if (! Schema::hasColumn('products', 'photo_mime')) {
                $table->string('photo_mime', 100)->nullable()->after('photo');
            }

            if (! Schema::hasColumn('products', 'photo_data')) {
                $table->longText('photo_data')->nullable()->after('photo_mime');
            }
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            if (Schema::hasColumn('products', 'photo_data')) {
                $table->dropColumn('photo_data');
            }

            if (Schema::hasColumn('products', 'photo_mime')) {
                $table->dropColumn('photo_mime');
            }
        });
    }
};
