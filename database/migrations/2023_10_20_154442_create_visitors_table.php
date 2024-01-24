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
        Schema::create('visitors', function (Blueprint $table) {
            $table->id();
            $table->string('username')->unique();
            $table->string('password')->nullable();
            $table->string('salutation');
            $table->string('name');
            $table->string('mobile_number');
            $table->string('email');
            $table->unsignedBigInteger('category_id');
            $table->string('organization');
            $table->string('designation');
            $table->string('known_source');
            $table->string('reason_for_visit')->nullable();
            $table->boolean('newsletter')->default(0);
            $table->string('proof_type')->nullable();
            $table->string('proof_id')->unique()->nullable();
            $table->string('registration_type');
            $table->json('_meta')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->softDeletes();
            $table->unsignedBigInteger('deleted_by')->nullable();
            $table->foreign('created_by')->references('id')->on('users');
            $table->foreign('updated_by')->references('id')->on('users');
            $table->foreign('deleted_by')->references('id')->on('users');
            $table->foreign('category_id')->references('id')->on('categories');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('visitors');
    }
};
