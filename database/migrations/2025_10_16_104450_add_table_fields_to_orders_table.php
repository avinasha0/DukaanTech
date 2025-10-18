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
            $table->enum('status', ['OPEN', 'CLOSED', 'CANCELLED'])->default('OPEN')->after('id');
            $table->unsignedBigInteger('table_id')->nullable()->after('status');
            $table->index('table_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropIndex(['table_id']);
            $table->dropColumn(['status', 'table_id']);
        });
    }
};