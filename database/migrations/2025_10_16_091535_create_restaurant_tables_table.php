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
        Schema::create('restaurant_tables', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained('accounts');
            $table->foreignId('outlet_id')->constrained('outlets');
            $table->string('name'); // T1, T2, etc.
            $table->enum('status', ['free', 'occupied', 'reserved'])->default('free');
            $table->integer('capacity')->default(4);
            $table->enum('shape', ['round', 'rectangular', 'oval', 'square'])->default('square');
            $table->string('type')->default('standard');
            $table->text('description')->nullable();
            $table->decimal('total_amount', 10, 2)->default(0.00);
            $table->json('orders')->nullable(); // Store current orders for this table
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            $table->index(['tenant_id', 'outlet_id']);
            $table->unique(['tenant_id', 'outlet_id', 'name']); // Unique table name per outlet
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('restaurant_tables');
    }
};