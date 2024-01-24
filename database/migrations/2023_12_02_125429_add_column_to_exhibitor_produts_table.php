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
        Schema::table('exhibitor_products', function (Blueprint $table) {
            if (!Schema::hasColumn('exhibitor_products', '_meta')) {
                $table->json('_meta')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('exhibitor_produts', function (Blueprint $table) {
            if (Schema::hasColumn('exhibitor_products', '_meta')) {
                $table->dropColumn('_meta');
            }
        });
    }
};
