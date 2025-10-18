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
        // First create tax_groups table if it doesn't exist
        if (!Schema::hasTable('tax_groups')) {
            Schema::create('tax_groups', function (Blueprint $table) {
                $table->id();
                $table->foreignId('tenant_id')->constrained('accounts');
                $table->string('name');
                $table->string('code')->unique();
                $table->text('description')->nullable();
                $table->boolean('is_active')->default(true);
                $table->timestamps();
                
                $table->index(['tenant_id', 'is_active']);
            });
        }

        Schema::table('tax_rates', function (Blueprint $table) {
            // Add columns only if they don't exist
            if (!Schema::hasColumn('tax_rates', 'name')) {
                $table->string('name')->after('code');
            }
            if (!Schema::hasColumn('tax_rates', 'description')) {
                $table->text('description')->nullable()->after('name');
            }
            if (!Schema::hasColumn('tax_rates', 'tax_group_id')) {
                $table->foreignId('tax_group_id')->nullable()->constrained('tax_groups')->after('tenant_id');
            }
            if (!Schema::hasColumn('tax_rates', 'calculation_type')) {
                $table->enum('calculation_type', ['percentage', 'fixed_amount'])->default('percentage')->after('rate');
            }
            if (!Schema::hasColumn('tax_rates', 'fixed_amount')) {
                $table->decimal('fixed_amount', 10, 2)->nullable()->after('calculation_type');
            }
            if (!Schema::hasColumn('tax_rates', 'is_compound')) {
                $table->boolean('is_compound')->default(false)->after('fixed_amount');
            }
            if (!Schema::hasColumn('tax_rates', 'applicable_items')) {
                $table->json('applicable_items')->nullable()->after('is_compound');
            }
            if (!Schema::hasColumn('tax_rates', 'applicable_order_types')) {
                $table->json('applicable_order_types')->nullable()->after('applicable_items');
            }
            if (!Schema::hasColumn('tax_rates', 'regional_settings')) {
                $table->json('regional_settings')->nullable()->after('applicable_order_types');
            }
            if (!Schema::hasColumn('tax_rates', 'is_active')) {
                $table->boolean('is_active')->default(true)->after('regional_settings');
            }
            if (!Schema::hasColumn('tax_rates', 'effective_from')) {
                $table->datetime('effective_from')->nullable()->after('is_active');
            }
            if (!Schema::hasColumn('tax_rates', 'effective_until')) {
                $table->datetime('effective_until')->nullable()->after('effective_from');
            }
        });

        // Add indexes if they don't exist
        Schema::table('tax_rates', function (Blueprint $table) {
            try {
                $table->index(['tenant_id', 'is_active']);
            } catch (\Exception $e) {
                // Index might already exist, ignore
            }
            try {
                $table->index(['tax_group_id', 'is_active']);
            } catch (\Exception $e) {
                // Index might already exist, ignore
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tax_rates', function (Blueprint $table) {
            if (Schema::hasColumn('tax_rates', 'tax_group_id')) {
                $table->dropForeign(['tax_group_id']);
            }
            $table->dropColumn([
                'name', 'description', 'tax_group_id', 'calculation_type', 
                'fixed_amount', 'is_compound', 'applicable_items', 
                'applicable_order_types', 'regional_settings', 'is_active',
                'effective_from', 'effective_until'
            ]);
        });
    }

};