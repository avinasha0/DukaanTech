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
        Schema::table('orders', function (Blueprint $table) {
            $table->foreignId('order_type_id')->nullable()->constrained('order_types')->after('outlet_id');
            $table->string('customer_name')->nullable()->after('order_type_id');
            $table->string('customer_phone')->nullable()->after('customer_name');
            $table->text('delivery_address')->nullable()->after('customer_phone');
            $table->decimal('delivery_fee', 10, 2)->default(0.00)->after('delivery_address');
            $table->text('special_instructions')->nullable()->after('delivery_fee');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn([
                'order_type_id',
                'customer_name',
                'customer_phone', 
                'delivery_address',
                'delivery_fee',
                'special_instructions'
            ]);
        });
    }
};