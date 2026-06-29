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
        Schema::create('spare_parts', function (Blueprint $table) {
            $table->id();

            $table->string('name');
            $table->string('sku')->unique();
            $table->string('category')->nullable();
            $table->integer('stock_quantity')->default(0);
            $table->integer('min_stock')->default(0);
            $table->integer('max_stock')->nullable();
            $table->decimal('price', 10, 2)->default(0);
            $table->string('supplier')->nullable();
            $table->string('location')->nullable();
            $table->string('warehouse')->nullable();
            $table->string('shelf')->nullable();
            $table->text('description')->nullable();
            $table->foreignId('equipment_id')->nullable()->constrained()->onDelete('set null');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('spare_parts');
    }
};
