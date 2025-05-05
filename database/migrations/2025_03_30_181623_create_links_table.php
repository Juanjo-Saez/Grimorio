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
        Schema::create('links', function (Blueprint $table) {
            $table->id(); // Primary Key autoincremental
            $table->foreignId('note_id')->constrained()->onDelete('cascade');
            $table->boolean('read_only')->default(true);
            $table->date('expiry_date')->nullable();
            $table->string('shortURL')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('links');
    }
};
