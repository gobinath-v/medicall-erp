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
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'mobile_number')) {
                $table->string('mobile_number')->nullable();
            }

            if (!Schema::hasColumn('users', 'department_id')) {
                $table->integer('department_id')->default(0);
            }

            if (!Schema::hasColumn('users', 'level')) {
                $table->char('level', 2)->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $columnsToDrop = ['mobile_number', 'department_id', 'level'];

            foreach ($columnsToDrop as $column) {
                $table->dropIfExists($column);
            }
        });
    }
};
