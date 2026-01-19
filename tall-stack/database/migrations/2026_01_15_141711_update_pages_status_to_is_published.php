<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Add is_published boolean column
        Schema::table('pages', function (Blueprint $table) {
            $table->boolean('is_published')->default(true)->after('display_order');
        });

        // Migrate existing data: 'published' status → is_published = true
        DB::table('pages')->update([
            'is_published' => DB::raw("CASE WHEN status = 'published' THEN true ELSE false END")
        ]);

        // Drop old status column
        Schema::table('pages', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Add status column back
        Schema::table('pages', function (Blueprint $table) {
            $table->enum('status', ['published', 'draft'])->default('published')->after('display_order');
        });

        // Migrate data back: is_published → status
        DB::table('pages')->update([
            'status' => DB::raw("CASE WHEN is_published = true THEN 'published' ELSE 'draft' END")
        ]);

        // Drop is_published column
        Schema::table('pages', function (Blueprint $table) {
            $table->dropColumn('is_published');
        });
    }
};
