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
        Schema::create('bill_templates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained('accounts');
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->boolean('is_default')->default(false);
            $table->boolean('is_active')->default(true);
            $table->json('template_config')->nullable(); // Store template configuration
            $table->json('header_config')->nullable(); // Logo, restaurant name, address settings
            $table->json('footer_config')->nullable(); // Footer text, QR code settings
            $table->json('item_config')->nullable(); // Item display settings
            $table->json('payment_config')->nullable(); // Payment method display settings
            $table->timestamps();
            
            $table->index(['tenant_id', 'is_active']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bill_templates');
    }
};
