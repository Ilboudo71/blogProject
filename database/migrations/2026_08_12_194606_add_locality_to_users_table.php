<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('users', 'locality')) {
            Schema::table('users', function (Blueprint $table) {
                $table->string('locality')->nullable();
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('users', 'locality')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('locality');
            });
        }
    }
};
