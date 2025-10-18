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
        Schema::table('restaurant_tables', function (Blueprint $table) {
            // Only add current_order_id if status already exists
            if (!Schema::hasColumn('restaurant_tables', 'current_order_id')) {
                $table->unsignedBigInteger('current_order_id')->nullable()->after('status');
                $table->index('current_order_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('restaurant_tables', function (Blueprint $table) {
            $table->dropIndex(['current_order_id']);
            $table->dropColumn(['status', 'current_order_id']);
        });
    }
};