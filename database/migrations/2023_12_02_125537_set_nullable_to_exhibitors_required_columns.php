<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public $tablename = 'exhibitors';
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table($this->tablename, function (Blueprint $table) {
            $table->unsignedBigInteger('category_id')->nullable(true)->change();
            $table->string('known_source')->nullable(true)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table($this->tablename, function (Blueprint $table) {
            $table->unsignedBigInteger('category_id')->nullable(false)->change();
            $table->string('known_source')->nullable(false)->change();
        });
    }
};
