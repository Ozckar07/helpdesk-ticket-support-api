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
        Schema::create('ticket_attachments', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();

            $table->foreignId('ticket_id')
                ->constrained('tickets')
                ->cascadeOnDelete();

            $table->foreignId('ticket_message_id')
                ->nullable()
                ->constrained('ticket_messages')
                ->nullOnDelete();

            $table->foreignId('uploaded_by_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->string('original_name', 255);
            $table->string('stored_name', 255);
            $table->string('disk', 50)->default('local');
            $table->string('path', 2048);
            $table->string('mime_type', 150)->nullable();
            $table->string('extension', 20)->nullable();
            $table->unsignedBigInteger('size')->default(0);

            $table->timestampsTz();
            $table->softDeletesTz();

            $table->index('ticket_id');
            $table->index('ticket_message_id');
            $table->index('uploaded_by_id');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ticket_attachments');
    }
};
