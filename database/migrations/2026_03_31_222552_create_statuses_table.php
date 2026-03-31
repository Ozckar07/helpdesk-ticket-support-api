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
        Schema::create('statuses', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();

            $table->string('name', 80);
            $table->string('code', 50)->unique();
            $table->unsignedSmallInteger('sort_order')->unique();
            $table->string('color', 20)->nullable();

            $table->boolean('is_system')->default(true);
            $table->boolean('is_final')->default(false)->index();
            $table->boolean('is_active')->default(true)->index();

            $table->timestampsTz();
            $table->softDeletesTz();

            $table->index(['is_active', 'sort_order']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('statuses');
    }
};
