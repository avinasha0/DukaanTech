<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE orders MODIFY COLUMN status ENUM('OPEN','CLOSED','CANCELLED','PAID') NOT NULL DEFAULT 'OPEN'");
        } else {
            // For SQLite and other databases, we need to recreate the column
            Schema::table('orders', function (Blueprint $table) {
                $table->dropColumn('status');
            });
            Schema::table('orders', function (Blueprint $table) {
                $table->enum('status', ['OPEN', 'CLOSED', 'CANCELLED', 'PAID'])->default('OPEN')->after('id');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE orders MODIFY COLUMN status ENUM('OPEN','CLOSED','CANCELLED') NOT NULL DEFAULT 'OPEN'");
        } else {
            // For SQLite and other databases, we need to recreate the column
            Schema::table('orders', function (Blueprint $table) {
                $table->dropColumn('status');
            });
            Schema::table('orders', function (Blueprint $table) {
                $table->enum('status', ['OPEN', 'CLOSED', 'CANCELLED'])->default('OPEN')->after('id');
            });
        }
    }
};
