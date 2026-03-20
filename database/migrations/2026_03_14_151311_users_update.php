<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Ajoute les colonnes traversée à la table users.
 *
 *   php artisan migrate
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {

            // Clé de la traversée choisie (regard | presence | absolu)
            $table->enum('traversee', ['regard', 'presence', 'absolu'])
                  ->nullable()
                  ->after('role')
                  ->comment('Traversée choisie lors de l\'inscription');

            // Libellé lisible (Regard / Présence / Absolu)
            $table->string('traversee_label', 50)
                  ->nullable()
                  ->after('traversee');

            // Tarif affiché (600 € / 1 000 € / Prix sur demande)
            $table->string('traversee_prix', 30)
                  ->nullable()
                  ->after('traversee_label');

            // Index pour filtrer par traversée dans le CRM interne
            $table->index('traversee');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex(['traversee']);
            $table->dropColumn(['traversee', 'traversee_label', 'traversee_prix']);
        });
    }
};