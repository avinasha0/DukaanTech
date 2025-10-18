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
        // Add logo fields to accounts table
        Schema::table('accounts', function (Blueprint $table) {
            $table->string('logo_path')->nullable()->after('settings');
            $table->string('logo_url')->nullable()->after('logo_path');
        });

        // Add logo fields to outlets table
        Schema::table('outlets', function (Blueprint $table) {
            $table->string('logo_path')->nullable()->after('address');
            $table->string('logo_url')->nullable()->after('logo_path');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('accounts', function (Blueprint $table) {
            $table->dropColumn(['logo_path', 'logo_url']);
        });

        Schema::table('outlets', function (Blueprint $table) {
            $table->dropColumn(['logo_path', 'logo_url']);
        });
    }
};
