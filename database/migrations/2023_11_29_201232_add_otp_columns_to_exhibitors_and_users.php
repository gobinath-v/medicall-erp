<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('exhibitors', function (Blueprint $table) {
            if (!Schema::hasColumn('exhibitors', 'otp')) {
                $table->string('otp')->nullable();
            }
            if (!Schema::hasColumn('exhibitors', 'otp_expired_at')) {
                $table->timestamp('otp_expired_at')->nullable();
            }
        });

        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'otp')) {
                $table->string('otp')->nullable();
            }
            if (!Schema::hasColumn('users', 'otp_expired_at')) {
                $table->timestamp('otp_expired_at')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('exhibitors', function (Blueprint $table) {
            if (Schema::hasColumn('exhibitors', 'otp')) {
                $table->dropColumn('otp');
            }
            if (Schema::hasColumn('exhibitors', 'otp_expired_at')) {
                $table->dropColumn('otp_expired_at');
            }
        });

        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'otp')) {
                $table->dropColumn('otp');
            }
            if (Schema::hasColumn('users', 'otp_expired_at')) {
                $table->dropColumn('otp_expired_at');
            }
        });
    }
};
