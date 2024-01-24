<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public $tableName = 'visitors';

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table($this->tableName, function (Blueprint $table) {
            $table->unsignedBigInteger('category_id')->nullable()->change();
            $table->string('organization')->nullable()->change();
            $table->string('designation')->nullable()->change();
            $table->string('known_source')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table($this->tableName, function (Blueprint $table) {
            $table->unsignedBigInteger('category_id')->nullable(false)->change();
            $table->string('organization')->nullable(false)->change();
            $table->string('designation')->nullable(false)->change();
            $table->string('known_source')->nullable(false)->change();
        });
    }
};
