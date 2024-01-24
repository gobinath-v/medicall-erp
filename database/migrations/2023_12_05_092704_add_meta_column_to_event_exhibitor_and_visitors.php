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
        Schema::table('event_exhibitors', function (Blueprint $table) {
            if (!Schema::hasColumn('event_exhibitors', '_meta')) {
                $table->json('_meta')->nullable();
            }
        });

        Schema::table('event_visitors', function (Blueprint $table) {
            if (!Schema::hasColumn('event_visitors', '_meta')) {
                $table->json('_meta')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('event_exhibitors', function (Blueprint $table) {
            if (Schema::hasColumn('event_exhibitors', '_meta')) {
                $table->dropColumn('_meta');
            }
        });

        Schema::table('event_visitors', function (Blueprint $table) {
            if (Schema::hasColumn('event_visitors', '_meta')) {
                $table->dropColumn('_meta');
            }
        });
    }
};
