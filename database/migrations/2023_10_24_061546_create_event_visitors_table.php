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
        Schema::create('event_visitors', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('event_id')->default(0);
            $table->unsignedBigInteger('visitor_id');
            $table->boolean('is_visited')->default(0);
            $table->json('product_looking')->nullable();
            // Uncomment once event master ready
            // $table->foreign('event_id')->references('id')->on('events');
            $table->foreign('visitor_id')->references('id')->on('visitors');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('event_visitors');
    }
};
