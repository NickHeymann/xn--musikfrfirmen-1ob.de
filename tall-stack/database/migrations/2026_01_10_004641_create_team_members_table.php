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
        Schema::create('team_members', function (Blueprint $table) {
            $table->id();
            $table->string('name');                     // "Jonas Glamann", "Nick Heymann"
            $table->string('role');                     // "Direkter Kontakt vor Ort"
            $table->string('role_second_line')->nullable(); // "Koordination von Band + Technik"
            $table->string('image');                    // "/images/team/jonas.png"
            $table->string('bio_title')->nullable();    // "Der Kreative"
            $table->text('bio_text')->nullable();       // Full biography
            $table->string('image_class')->nullable();  // "img1", "img2"
            $table->enum('position', ['left', 'right'])->default('left');
            $table->integer('display_order')->default(0);
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('team_members');
    }
};
