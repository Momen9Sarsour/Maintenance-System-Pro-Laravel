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
        Schema::create('maintenance_schedules', function (Blueprint $table) {
            $table->id();

            $table->string('title');
            $table->enum('type', ['preventive', 'corrective', 'inspection']);
            $table->enum('frequency', ['daily', 'weekly', 'monthly', 'quarterly', 'semi_annual', 'annual']);
            $table->integer('interval_value')->default(1);
            $table->date('start_date');
            $table->date('next_due_date');
            $table->date('last_completed_date')->nullable();
            $table->enum('status', ['active', 'overdue', 'completed'])->default('active');
            $table->foreignId('equipment_id')->constrained('equipment');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('maintenance_schedules');
    }
};
