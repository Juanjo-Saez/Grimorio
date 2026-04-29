<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Update tags table with user_id and proper unique constraint
     */
    public function up(): void
    {
        Schema::table('tags', function (Blueprint $table) {
            if (!Schema::hasColumn('tags', 'user_id')) {
                $table->foreignId('user_id')->constrained('users')->cascadeOnDelete()->after('id');
            }
        });
        
        // Create unique constraint on (user_id, tagname)
        Schema::table('tags', function (Blueprint $table) {
            $table->unique(['user_id', 'tagname']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tags', function (Blueprint $table) {
            $table->dropUnique(['user_id', 'tagname']);
            $table->dropForeignKey(['user_id']);
            $table->dropColumn('user_id');
        });
    }
};
