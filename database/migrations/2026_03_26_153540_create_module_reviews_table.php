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

        Schema::create('module_reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('carnet_id')->constrained()->onDelete('cascade');
            $table->unsignedTinyInteger('module_number'); // 1-9 (EN CLAIR)
            $table->text('synthesis_encrypted')->nullable();   // CHIFFRÉ
            $table->text('learnings_encrypted')->nullable();   // CHIFFRÉ
            $table->text('intentions_encrypted')->nullable();  // CHIFFRÉ
            $table->boolean('is_completed')->default(false);
            $table->timestamps();
            $table->unique(['carnet_id', 'module_number']);
        });
    
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('module_reviews');
    }
};
