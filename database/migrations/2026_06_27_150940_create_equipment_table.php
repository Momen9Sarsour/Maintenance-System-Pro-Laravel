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
        Schema::create('equipment', function (Blueprint $table) {
            $table->id();

            $table->string('name');
            $table->string('model')->nullable();
            $table->string('serial_number')->unique();
            $table->string('manufacturer')->nullable();
            $table->string('location')->nullable();
            $table->string('building')->nullable();
            $table->string('floor')->nullable();
            $table->enum('status', ['operational', 'maintenance', 'out_of_service', 'retired'])->default('operational');
            $table->date('installation_date')->nullable();
            $table->date('warranty_expiry')->nullable();
            $table->text('description')->nullable();
            $table->foreignId('assigned_technician_id')->nullable()->constrained('users')->onDelete('set null');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('equipment');
    }
};
