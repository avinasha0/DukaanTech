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
        Schema::create('kot_routes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained('accounts');
            $table->foreignId('outlet_id')->constrained('outlets');
            $table->foreignId('category_id')->constrained('categories');
            $table->string('station');
            $table->foreignId('printer_id')->nullable()->constrained('printers');
            $table->timestamps();
            $table->unique(['tenant_id', 'outlet_id', 'category_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kot_routes');
    }
};
