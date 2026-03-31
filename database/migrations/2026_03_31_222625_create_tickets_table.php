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
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();

            $table->string('ticket_number', 30)->unique();
            $table->string('subject', 180);
            $table->longText('description');

            $table->foreignId('customer_id')
                ->constrained('users')
                ->restrictOnDelete();

            $table->foreignId('assigned_agent_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->foreignId('category_id')
                ->constrained('categories')
                ->restrictOnDelete();

            $table->foreignId('priority_id')
                ->constrained('priorities')
                ->restrictOnDelete();

            $table->foreignId('status_id')
                ->constrained('statuses')
                ->restrictOnDelete();

            $table->timestampTz('first_response_at')->nullable();
            $table->timestampTz('resolved_at')->nullable();
            $table->timestampTz('closed_at')->nullable();

            $table->timestampsTz();
            $table->softDeletesTz();

            $table->index('customer_id');
            $table->index('assigned_agent_id');
            $table->index('category_id');
            $table->index('priority_id');
            $table->index('status_id');
            $table->index('created_at');
            $table->index('resolved_at');
            $table->index('closed_at');
            $table->index(['status_id', 'priority_id']);
            $table->index(['assigned_agent_id', 'status_id']);
            $table->index(['customer_id', 'status_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};
