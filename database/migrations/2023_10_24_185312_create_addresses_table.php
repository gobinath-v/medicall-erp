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
        Schema::create('addresses', function (Blueprint $table) {
            $table->id();
            $table->string('address')->nullable(); // Address varchar column
            $table->string('pincode')->nullable(); // Pincode varchar column
            $table->string('city')->nullable(); // City varchar column
            $table->string('state')->nullable(); // State varchar column
            $table->string('country')->nullable(); // Country varchar column
            $table->unsignedBigInteger('addressable_id'); // Foreign key for entity ID
            $table->string('addressable_type'); // Entity type (exhibitor, event, visitor)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('addresses');
    }
};
