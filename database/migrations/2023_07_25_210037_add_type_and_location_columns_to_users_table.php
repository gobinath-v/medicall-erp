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

            if (!Schema::hasColumn('users', 'emp_id')) {
                $table->string('emp_id', 30)->nullable()->after('password');
            }

            if (!Schema::hasColumn('users', 'type')) {
                $table->string('type', 30)->default('user')->after('password');
            }

            if (!Schema::hasColumn('users', 'location')) {
                $table->integer('location_id')->nullable()->after('type');
            }

            if (!Schema::hasColumn('users', 'is_active')) {
                $table->boolean('is_active')->default(1)->after('location_id');
            }

            if (!Schema::hasColumn('users', 'created_by')) {
                $table->integer('created_by')->nullable()->after('is_active');
            }

            if (!Schema::hasColumn('users', 'updated_by')) {
                $table->integer('updated_by')->nullable()->after('created_by');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            foreach (['emp_id', 'type', 'location_id', 'is_active', 'created_by', 'updated_by'] as $column) {
                if (Schema::hasColumn('users', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
