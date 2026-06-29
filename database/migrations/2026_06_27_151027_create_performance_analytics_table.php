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
        Schema::create('performance_analytics', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->integer('total_tasks')->default(0);
            $table->integer('completed_tasks')->default(0);
            $table->integer('pending_tasks')->default(0);
            $table->integer('cancelled_tasks')->default(0);
            $table->integer('on_hold_tasks')->default(0);
            $table->decimal('completion_rate', 5, 2)->default(0);
            $table->decimal('on_time_rate', 5, 2)->default(0);
            $table->decimal('first_time_fix_rate', 5, 2)->default(0);
            $table->decimal('avg_repair_time', 10, 2)->default(0);
            $table->decimal('customer_rating', 3, 2)->default(0);
            $table->date('period_start');
            $table->date('period_end');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('performance_analytics');
    }
};
