<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::getConnection()->getDriverName() !== 'mysql') {
            return;
        }

        DB::statement("ALTER TABLE orders MODIFY COLUMN state ENUM(
            'PENDING_QR_APPROVAL',
            'NEW',
            'IN_KITCHEN',
            'READY',
            'SERVED',
            'BILLED',
            'CLOSED'
        ) NOT NULL DEFAULT 'NEW'");
    }

    public function down(): void
    {
        if (Schema::getConnection()->getDriverName() !== 'mysql') {
            return;
        }

        DB::statement("ALTER TABLE orders MODIFY COLUMN state ENUM(
            'NEW',
            'IN_KITCHEN',
            'READY',
            'SERVED',
            'BILLED',
            'CLOSED'
        ) NOT NULL DEFAULT 'NEW'");
    }
};
