<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (! Schema::hasColumn('users', 'is_premium')) {
                $table->boolean('is_premium')->default(false)->after('role');
            }

            if (! Schema::hasColumn('users', 'premium_activated_at')) {
                $table->timestamp('premium_activated_at')->nullable()->after('is_premium');
            }

            if (! Schema::hasColumn('users', 'premium_expires_at')) {
                $table->timestamp('premium_expires_at')->nullable()->after('premium_activated_at');
            }

            if (! Schema::hasColumn('users', 'premium_payment_ref')) {
                $table->string('premium_payment_ref')->nullable()->after('premium_expires_at');
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $columns = collect(['is_premium', 'premium_activated_at', 'premium_expires_at', 'premium_payment_ref'])
                ->filter(fn (string $column) => Schema::hasColumn('users', $column))
                ->values()
                ->all();

            if ($columns !== []) {
                $table->dropColumn($columns);
            }
        });
    }
};
