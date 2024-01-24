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
            if (!Schema::hasColumn('event_exhibitors', 'sales_person_id')) {
                $table->unsignedBigInteger('sales_person_id')->nullable();
                $table->foreign('sales_person_id')->references('id')->on('users');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('event_exhibitors', function (Blueprint $table) {
            if (Schema::hasColumn('event_exhibitors', 'sales_person_id')) {
                if (Schema::hasForeignKey('event_exhibitors', 'sales_person_id')) {
                    $table->dropForeign('event_exhibitors_sales_person_id_foreign');
                }
                $table->dropColumn('sales_person_id');
            }
        });
    }
};
