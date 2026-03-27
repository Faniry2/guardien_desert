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
        Schema::create('carnets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->text('intention_encrypted')->nullable();    // CHIFFRÉ
            $table->text('couverture_encrypted')->nullable();   // CHIFFRÉ
            $table->text('regles_encrypted')->nullable();       // CHIFFRÉ
            $table->date('start_date');                         // EN CLAIR
            $table->enum('status', ['draft', 'active', 'completed'])->default('active');
            $table->timestamps();
            $table->unique('user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
