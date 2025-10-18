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
        Schema::table('accounts', function (Blueprint $table) {
            $table->string('email')->nullable()->after('phone');
            $table->text('description')->nullable()->after('email');
            $table->string('website')->nullable()->after('description');
            $table->string('industry')->nullable()->after('website');
            $table->string('company_size')->nullable()->after('industry');
            $table->string('founded_year')->nullable()->after('company_size');
            $table->json('contact_info')->nullable()->after('founded_year'); // Additional contact details
            $table->json('business_hours')->nullable()->after('contact_info'); // Operating hours
            $table->json('social_media')->nullable()->after('business_hours'); // Social media links
            $table->json('tax_settings')->nullable()->after('social_media'); // Tax configuration
            $table->json('notification_settings')->nullable()->after('tax_settings'); // Notification preferences
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('accounts', function (Blueprint $table) {
            $table->dropColumn([
                'email',
                'description', 
                'website',
                'industry',
                'company_size',
                'founded_year',
                'contact_info',
                'business_hours',
                'social_media',
                'tax_settings',
                'notification_settings'
            ]);
        });
    }
};
