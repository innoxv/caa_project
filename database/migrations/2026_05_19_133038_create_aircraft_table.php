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
        Schema::create('aircraft', function (Blueprint $table) {
            $table->id();
            $table->string('manufacturer');
            $table->string('model');
            $table->string('serial_number');
            $table->integer('year');
            $table->string('registration_mark')->nullable();
            $table->string('owner_name');
            $table->string('owner_address');
            $table->string('owner_email');
            $table->string('owner_phone');
            $table->enum('status', ['active', 'pending', 'suspended'])->default('pending');
            $table->date('issue_date')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('aircraft');
    }
};
