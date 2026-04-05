<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('qr_razorpay_checkouts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tenant_id');
            $table->json('payload');
            $table->unsignedBigInteger('amount_paise');
            $table->string('currency', 8)->default('INR');
            $table->string('razorpay_order_id')->unique();
            $table->timestamp('expires_at');
            $table->timestamp('completed_at')->nullable();
            $table->unsignedBigInteger('order_id')->nullable();
            $table->timestamps();

            $table->index(['tenant_id', 'expires_at']);
        });

        Schema::create('razorpay_qr_payment_keys', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tenant_id');
            $table->string('razorpay_payment_id')->unique();
            $table->unsignedBigInteger('order_id');
            $table->timestamps();

            $table->index('tenant_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('razorpay_qr_payment_keys');
        Schema::dropIfExists('qr_razorpay_checkouts');
    }
};
