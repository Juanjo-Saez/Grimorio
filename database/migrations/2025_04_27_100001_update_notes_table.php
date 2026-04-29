<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Update notes table with title, content, description and proper timestamps
     */
    public function up(): void
    {
        Schema::table('notes', function (Blueprint $table) {
            // Remove filename if exists
            if (Schema::hasColumn('notes', 'filename')) {
                $table->dropColumn('filename');
            }
            
            // Add new columns if not exist
            if (!Schema::hasColumn('notes', 'title')) {
                $table->string('title', 255)->after('user_id');
            }
            if (!Schema::hasColumn('notes', 'content')) {
                $table->longText('content')->nullable()->after('title');
            }
            if (!Schema::hasColumn('notes', 'description')) {
                $table->string('description', 500)->nullable()->after('content');
            }
            if (!Schema::hasColumn('notes', 'created_at')) {
                $table->timestamps();
            }
        });
        
        // Create unique constraint on (user_id, title)
        Schema::table('notes', function (Blueprint $table) {
            $table->unique(['user_id', 'title']);
            // Add FULLTEXT index (MySQL specific - SQLite not supported)
            if (DB::getDriverName() === 'mysql') {
                DB::statement('ALTER TABLE notes ADD FULLTEXT INDEX ft_search (title, content, description)');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('notes', function (Blueprint $table) {
            $table->dropUnique(['user_id', 'title']);
            if (DB::getDriverName() === 'mysql') {
                DB::statement('ALTER TABLE notes DROP INDEX ft_search');
            }
            $table->dropColumn(['title', 'content', 'description', 'created_at', 'updated_at']);
            $table->string('filename');
        });
    }
};
