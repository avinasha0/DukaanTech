<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('roles', function (Blueprint $table) {
            // Drop global unique constraints
            if (Schema::hasColumn('roles', 'name')) {
                $table->dropUnique('roles_name_unique');
            }
            if (Schema::hasColumn('roles', 'slug')) {
                $table->dropUnique('roles_slug_unique');
            }

            // Add tenant-scoped unique constraints
            $table->unique(['tenant_id', 'name'], 'roles_tenant_name_unique');
            $table->unique(['tenant_id', 'slug'], 'roles_tenant_slug_unique');
        });
    }

    public function down(): void
    {
        Schema::table('roles', function (Blueprint $table) {
            // Drop tenant-scoped unique constraints
            $table->dropUnique('roles_tenant_name_unique');
            $table->dropUnique('roles_tenant_slug_unique');

            // Restore global unique constraints
            $table->unique('name');
            $table->unique('slug');
        });
    }
};


