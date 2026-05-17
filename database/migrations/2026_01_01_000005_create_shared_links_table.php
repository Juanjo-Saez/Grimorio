<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('shared_links', function (Blueprint $table) {
            $table->id();
            $table->foreignId('note_id')->constrained()->cascadeOnDelete();
            $table->foreignId('owner_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('recipient_id')->nullable()->constrained('users')->cascadeOnDelete();
            $table->string('recipient_email')->nullable();
            $table->string('token', 64)->unique();
            $table->enum('access_level', ['read', 'edit'])->default('read');
            $table->timestamp('created_at')->useCurrent();

            $table->index('owner_id');
            $table->index('recipient_id');
            $table->index('token');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('shared_links');
    }
};
