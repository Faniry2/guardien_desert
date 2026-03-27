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
        Schema::create('final_reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('carnet_id')->constrained()->onDelete('cascade');
            $table->text('transformation_encrypted')->nullable();  // CHIFFRÉ
            $table->text('gratitude_encrypted')->nullable();       // CHIFFRÉ
            $table->text('letter_to_self_encrypted')->nullable();  // CHIFFRÉ
            $table->text('next_chapter_encrypted')->nullable();    // CHIFFRÉ
            $table->boolean('is_completed')->default(false);
            $table->timestamps();
            $table->unique('carnet_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('final_reviews');
    }
};
