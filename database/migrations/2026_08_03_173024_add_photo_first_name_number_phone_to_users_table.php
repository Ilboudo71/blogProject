<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('users', 'photo')) {
            Schema::table('users', function (Blueprint $table) {
                $table->string('photo')->nullable();
            });
        }

        if (! Schema::hasColumn('users', 'first_name')) {
            Schema::table('users', function (Blueprint $table) {
                $table->string('first_name')->nullable();
            });
        }

        if (! Schema::hasColumn('users', 'number_phone')) {
            Schema::table('users', function (Blueprint $table) {
                $table->string('number_phone')->nullable();
            });
        }
    }

    public function down(): void
    {
        $columns = collect(['photo', 'first_name', 'number_phone'])
            ->filter(fn (string $column) => Schema::hasColumn('users', $column))
            ->values()
            ->all();

        if ($columns !== []) {
            Schema::table('users', function (Blueprint $table) use ($columns) {
                $table->dropColumn($columns);
            });
        }
    }
};
