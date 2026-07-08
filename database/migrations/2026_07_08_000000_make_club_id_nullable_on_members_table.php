<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('members', function (Blueprint $table) {
            // Drop the existing foreign key and make club_id nullable
            $table->dropForeign(['club_id']);
            $table->dropUnique(['club_id', 'position_id', 'first_name', 'last_name']);
            $table->dropUnique(['club_id', 'slug']);
            $table->dropIndex(['club_id', 'contact_number']);

            $table->foreignId('club_id')->nullable()->change()->constrained('clubs')->cascadeOnDelete();

            // Re-create unique constraints (SQLite treats NULLs as distinct in uniques, which is fine)
            $table->unique(['club_id', 'position_id', 'first_name', 'last_name']);
            $table->unique(['club_id', 'slug']);
            $table->index(['club_id', 'contact_number']);
        });
    }

    public function down(): void
    {
        Schema::table('members', function (Blueprint $table) {
            $table->dropForeign(['club_id']);
            $table->dropUnique(['club_id', 'position_id', 'first_name', 'last_name']);
            $table->dropUnique(['club_id', 'slug']);
            $table->dropIndex(['club_id', 'contact_number']);

            $table->foreignId('club_id')->nullable(false)->change()->constrained('clubs')->cascadeOnDelete();

            $table->unique(['club_id', 'position_id', 'first_name', 'last_name']);
            $table->unique(['club_id', 'slug']);
            $table->index(['club_id', 'contact_number']);
        });
    }
};
