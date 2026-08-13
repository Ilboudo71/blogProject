<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (! Schema::hasColumn('users', 'photo')) {
                $table->string('photo')->nullable();
            }

            if (! Schema::hasColumn('users', 'first_name')) {
                $table->string('first_name')->nullable();
            }

            if (! Schema::hasColumn('users', 'number_phone')) {
                $table->string('number_phone')->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $columns = collect(['photo', 'first_name', 'number_phone'])
                ->filter(fn (string $column) => Schema::hasColumn('users', $column))
                ->all();

            if ($columns !== []) {
                $table->dropColumn($columns);
            }
        });
    }
};
