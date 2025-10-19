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
        Schema::table('users', function (Blueprint $table) {
            $table->string('timezone')->nullable()->after('tenant_id');
            $table->string('language', 10)->default('en')->after('timezone');
            $table->enum('theme', ['light', 'dark', 'auto'])->default('auto')->after('language');
            $table->json('notifications')->nullable()->after('theme');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['timezone', 'language', 'theme', 'notifications']);
        });
    }
};
