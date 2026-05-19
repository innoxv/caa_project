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
        Schema::create('mros', function (Blueprint $table) {
            $table->id();
            $table->string('certificate_no')->nullable();
            $table->string('name');
            $table->string('address');
            $table->string('country');
            $table->string('application_type');
            $table->json('ratings')->nullable();
            $table->string('accountable_manager');
            $table->string('quality_manager');
            $table->enum('status', ['approved', 'pending', 'expired'])->default('pending');
            $table->date('expiry_date')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mros');
    }
};
