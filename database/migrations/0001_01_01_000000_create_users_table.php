<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    // ─────────────────────────────────────────────────────────────────
    //  TABLE : users
    //  Couvre tous les champs du formulaire Renait-Sens
    // ─────────────────────────────────────────────────────────────────

    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {

            // ── Clé primaire ──────────────────────────────────────────
            $table->id();

            // ── Identité ──────────────────────────────────────────────
            $table->string('prenom',  100);
            $table->string('nom',     100);
            $table->string('name',    255);          // prénom + nom (colonne standard Laravel)
            $table->string('email',   255)->unique();
            $table->timestamp('email_verified_at')->nullable();

            // ── Téléphone ─────────────────────────────────────────────
            $table->string('telephone',     30)->nullable();   // numéro complet (indicatif + numéro)
            $table->string('tel_indicatif', 10)->nullable();   // indicatif seul (+261, +33…)

            // ── Adresse ───────────────────────────────────────────────
            $table->string('rue',             255)->nullable();
            $table->string('ville',           100)->nullable();
            $table->string('region',          100)->nullable();
            $table->string('codepostal',       20)->nullable();
            $table->char('pays', 2)->nullable();               // ISO 3166-1 alpha-2 (MG, FR…)
            $table->string('adresse_complete', 500)->nullable(); // adresse formatée sur une ligne

            // ── Préférences & Métadonnées ─────────────────────────────
            $table->string('timezone',          60)->nullable()->default('Indian/Antananarivo');
            $table->string('locale',            20)->nullable()->default('fr');
            $table->enum('theme_preference', ['night', 'dawn', 'noon'])->default('night');
            $table->string('source',           255)->nullable()->default('direct'); // utm / referrer
            $table->string('registered_at_client', 50)->nullable(); // ISO 8601 côté navigateur

            // ── Pacte de l'Aman ───────────────────────────────────────
            $table->boolean('pacte_aman_accepted')->default(false);
            $table->timestamp('pacte_aman_at')->nullable();

            // ── Rôle ──────────────────────────────────────────────────
            $table->string('role', 30)->default('nomade'); // nomade | guide | admin

            // ── Sécurité ──────────────────────────────────────────────
            $table->string('password');
            $table->rememberToken();

            // ── Timestamps Laravel ────────────────────────────────────
            $table->timestamps();   // created_at + updated_at
            $table->softDeletes();  // deleted_at  (suppression douce)

            // ── Index utiles ──────────────────────────────────────────
            $table->index('pays');
            $table->index('role');
            $table->index('theme_preference');
           
            $table->string('espo_lead_id', 24)
                  ->nullable()
                  ->after('source')
                  ->comment('ID du Lead correspondant dans EspoCRM');
 
            $table->index('espo_lead_id');
        
        });

        // ── Table de réinitialisation de mot de passe (standard Laravel) ──
        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        // ── Sessions (si vous utilisez database sessions) ─────────────
        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    // ─────────────────────────────────────────────────────────────────

    public function down(): void
    {
        Schema::dropIfExists('sessions');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('users');
    }
};