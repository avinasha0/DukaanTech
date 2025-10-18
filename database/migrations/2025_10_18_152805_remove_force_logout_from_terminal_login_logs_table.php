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
        // First, update any existing force_logout records to logout
        DB::table('terminal_login_logs')
            ->where('action', 'force_logout')
            ->update(['action' => 'logout']);

        // Then modify the enum column to remove force_logout
        DB::statement("ALTER TABLE terminal_login_logs MODIFY COLUMN action ENUM('login', 'logout', 'session_expired') NOT NULL");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Add back the force_logout option to the enum
        DB::statement("ALTER TABLE terminal_login_logs MODIFY COLUMN action ENUM('login', 'logout', 'force_logout', 'session_expired') NOT NULL");
    }
};
