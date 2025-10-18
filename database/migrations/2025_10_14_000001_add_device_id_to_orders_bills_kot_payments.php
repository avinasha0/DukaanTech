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
            $table->foreignId('device_id')->nullable()->after('outlet_id')->constrained('devices');
        });

        Schema::table('bills', function (Blueprint $table) {
            $table->foreignId('device_id')->nullable()->after('outlet_id')->constrained('devices');
        });

        Schema::table('kitchen_tickets', function (Blueprint $table) {
            $table->foreignId('device_id')->nullable()->after('outlet_id')->constrained('devices');
        });

        Schema::table('payments', function (Blueprint $table) {
            $table->foreignId('device_id')->nullable()->after('bill_id')->constrained('devices');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropConstrainedForeignId('device_id');
        });

        Schema::table('bills', function (Blueprint $table) {
            $table->dropConstrainedForeignId('device_id');
        });

        Schema::table('kitchen_tickets', function (Blueprint $table) {
            $table->dropConstrainedForeignId('device_id');
        });

        Schema::table('payments', function (Blueprint $table) {
            $table->dropConstrainedForeignId('device_id');
        });
    }
};


