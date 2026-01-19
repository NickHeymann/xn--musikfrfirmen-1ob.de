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
        Schema::create('pages', function (Blueprint $table) {
            $table->id();
            $table->string('title');                    // "Datenschutz", "Impressum"
            $table->string('slug')->unique();           // "datenschutz", "impressum"
            $table->text('content')->nullable();        // HTML content
            $table->enum('type', ['legal', 'content'])->default('content');
            $table->integer('display_order')->default(0);
            $table->enum('status', ['published', 'draft'])->default('published');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pages');
    }
};
