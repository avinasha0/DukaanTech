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
        Schema::create('discounts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained('accounts');
            $table->string('name');
            $table->string('code')->unique();
            $table->enum('type', ['percentage', 'fixed_amount', 'buy_x_get_y']);
            $table->decimal('value', 10, 2); // percentage or fixed amount
            $table->decimal('minimum_amount', 10, 2)->nullable(); // minimum order amount
            $table->decimal('maximum_discount', 10, 2)->nullable(); // maximum discount amount
            $table->integer('buy_quantity')->nullable(); // for buy_x_get_y type
            $table->integer('get_quantity')->nullable(); // for buy_x_get_y type
            $table->datetime('valid_from')->nullable();
            $table->datetime('valid_until')->nullable();
            $table->boolean('is_active')->default(true);
            $table->json('applicable_items')->nullable(); // specific items/categories
            $table->json('applicable_order_types')->nullable(); // dine-in, takeaway, etc.
            $table->integer('usage_limit')->nullable(); // total usage limit
            $table->integer('usage_count')->default(0); // current usage count
            $table->text('description')->nullable();
            $table->timestamps();
            
            $table->index(['tenant_id', 'is_active']);
            $table->index(['code', 'is_active']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('discounts');
    }
};