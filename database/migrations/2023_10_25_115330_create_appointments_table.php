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
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('event_id');
            $table->unsignedBigInteger('visitor_id');
            $table->unsignedBigInteger('exhibitor_id');
            $table->timestamp('scheduled_at');
            $table->string('status')->default('scheduled');
            $table->string('notes')->nullable();
            $table->json('_meta')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->unsignedBigInteger('cancelled_by')->nullable();
            $table->timestamp('cancelled_at')->nullable();
            $table->timestamps();

            // Define foreign key constraints
            $table->foreign('event_id')->references('id')->on('events');
            $table->foreign('visitor_id')->references('id')->on('visitors');
            $table->foreign('exhibitor_id')->references('id')->on('exhibitors');
            $table->foreign('updated_by')->references('id')->on('users');
            $table->foreign('cancelled_by')->references('id')->on('users');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('appointments');
    }
};
