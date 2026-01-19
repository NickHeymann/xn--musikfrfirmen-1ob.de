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
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description');
            $table->string('location');
            $table->dateTime('start_time');
            $table->dateTime('end_time');
            $table->integer('capacity');
            $table->decimal('price_per_musician', 10, 2);
            $table->integer('musicians_needed')->default(1);
            $table->string('music_style')->nullable(); // Jazz, Classical, Rock, etc.
            $table->enum('status', ['draft', 'published', 'booked', 'completed', 'cancelled'])->default('draft');
            $table->json('requirements')->nullable(); // Equipment, dress code, etc.
            $table->timestamps();

            $table->index('start_time');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
