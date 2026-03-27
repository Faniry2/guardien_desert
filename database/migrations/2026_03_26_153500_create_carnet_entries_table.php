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
        Schema::create('carnet_entries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('carnet_id')->constrained()->onDelete('cascade');

            // Métadonnées EN CLAIR (navigation, progression)
            $table->unsignedTinyInteger('day_number');
            $table->date('entry_date');
            $table->boolean('is_completed')->default(false);

            // Contenu CHIFFRÉ (AES-256-GCM base64)
            $table->text('title_encrypted')->nullable();
            $table->text('color_encrypted')->nullable();
            $table->text('mood_encrypted')->nullable();
            $table->text('highlight_encrypted')->nullable();
            $table->text('reflection_encrypted')->nullable();
            $table->text('questions_encrypted')->nullable();
            $table->text('free_writing_encrypted')->nullable();
            $table->text('awareness_encrypted')->nullable();
            $table->text('commitment_encrypted')->nullable();

            $table->timestamps();
            $table->unique(['carnet_id', 'day_number']);
            $table->index(['carnet_id', 'is_completed']);
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('carnet_entries');
    }
};
