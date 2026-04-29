<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Create shared_links table for note sharing feature
     */
    public function up(): void
    {
        Schema::create('shared_links', function (Blueprint $table) {
            $table->id();
            $table->foreignId('note_id')->constrained('notes')->cascadeOnDelete();
            $table->foreignId('owner_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('recipient_id')->constrained('users')->cascadeOnDelete();
            $table->string('token', 64)->unique();
            $table->enum('access_level', ['read', 'edit'])->default('read');
            $table->timestamp('created_at')->useCurrent();
            
            // Unique constraint to prevent duplicate shares to same user
            $table->unique(['note_id', 'recipient_id']);
            
            // Indices for common queries
            $table->index('owner_id');
            $table->index('recipient_id');
            $table->index('token');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shared_links');
    }
};
