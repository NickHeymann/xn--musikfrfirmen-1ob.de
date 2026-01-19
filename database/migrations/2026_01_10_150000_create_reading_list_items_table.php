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
        Schema::create('reading_list_items', function (Blueprint $table) {
            $table->id();
            $table->string('session_id')->index(); // Anonymous users via session
            $table->foreignId('blog_post_id')->constrained()->onDelete('cascade');
            $table->timestamp('added_at');
            $table->timestamps();

            // Prevent duplicate bookmarks
            $table->unique(['session_id', 'blog_post_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reading_list_items');
    }
};
