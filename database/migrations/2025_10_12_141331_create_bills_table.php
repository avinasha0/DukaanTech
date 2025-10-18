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
        Schema::create('bills', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained('accounts');
            $table->foreignId('outlet_id')->constrained('outlets');
            $table->foreignId('order_id')->constrained('orders');
            $table->string('invoice_no');
            $table->decimal('sub_total', 12, 2);
            $table->decimal('tax_total', 12, 2);
            $table->decimal('discount_total', 12, 2)->default(0);
            $table->decimal('round_off', 12, 2)->default(0);
            $table->decimal('net_total', 12, 2);
            $table->enum('state', ['OPEN', 'PAID', 'VOID'])->default('OPEN');
            $table->timestamps();
            $table->unique(['tenant_id', 'outlet_id', 'invoice_no']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bills');
    }
};
