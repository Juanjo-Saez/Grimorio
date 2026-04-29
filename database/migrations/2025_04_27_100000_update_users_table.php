<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Update users table with password_hash column
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Rename 'password' to 'password_hash' if exists
            if (Schema::hasColumn('users', 'password')) {
                $table->renameColumn('password', 'password_hash');
            }
            // Remove username if exists
            if (Schema::hasColumn('users', 'username')) {
                $table->dropColumn('username');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'password_hash')) {
                $table->renameColumn('password_hash', 'password');
            }
        });
    }
};
