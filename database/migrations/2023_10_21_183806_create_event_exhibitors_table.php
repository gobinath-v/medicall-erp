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
        Schema::create('event_exhibitors', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('event_id');
            $table->foreign('event_id')->references('id')->on('events');
            $table->unsignedBigInteger('exhibitor_id');
            $table->foreign('exhibitor_id')->references('id')->on('exhibitors');
            $table->string('stall_no')->nullable();
            $table->boolean('is_sponsorer')->default(0);
            $table->json('products')->nullable();
            $table->json('tags')->nullable();
            $table->integer('order')->nullable();
            $table->unsignedBigInteger('cancelled_by')->nullable();
            $table->foreign('cancelled_by')->references('id')->on('users');
            $table->timestamp('cancelled_at')->nullable(); // Corrected line: changed timestamps('cancelled_at') to timestamp('cancelled_at')
            $table->string('cancelled_reason')->nullable();
            $table->boolean('is_active')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('event_exhibitors');
    }
};
