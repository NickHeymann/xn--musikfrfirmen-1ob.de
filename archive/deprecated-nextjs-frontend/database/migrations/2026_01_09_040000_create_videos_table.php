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
        Schema::create('videos', function (Blueprint $table) {
            $table->id();
            $table->string('youtube_id', 20)->unique();
            $table->string('title');
            $table->text('description');
            $table->enum('category', ['beziehung', 'selbstfindung', 'hochsensibel', 'koerper'])->index();
            $table->timestamp('published_at')->index();
            $table->string('thumbnail_url')->nullable();
            $table->integer('views')->default(0);
            $table->boolean('featured')->default(false)->index();
            $table->timestamps();
            
            // Composite indexes for common queries
            $table->index(['category', 'published_at']);
            $table->index(['featured', 'published_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('videos');
    }
};
