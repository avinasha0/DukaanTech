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
        Schema::create('terminal_login_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tenant_id');
            $table->unsignedBigInteger('terminal_user_id');
            $table->unsignedBigInteger('device_id')->nullable();
            $table->string('session_token')->nullable();
            $table->enum('action', ['login', 'logout', 'force_logout', 'session_expired']);
            $table->string('ip_address', 45)->nullable();
            $table->string('user_agent')->nullable();
            $table->timestamp('logged_at');
            $table->json('metadata')->nullable(); // For additional data like device info, etc.
            $table->timestamps();
            
            // Indexes
            $table->index(['tenant_id', 'terminal_user_id']);
            $table->index(['action', 'logged_at']);
            $table->index('session_token');
            $table->index('logged_at');
            
            // Foreign keys
            $table->foreign('tenant_id')->references('id')->on('accounts')->onDelete('cascade');
            $table->foreign('terminal_user_id')->references('id')->on('terminal_users')->onDelete('cascade');
            $table->foreign('device_id')->references('id')->on('devices')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('terminal_login_logs');
    }
};