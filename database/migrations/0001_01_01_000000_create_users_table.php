<?php
// database/migrations/0001_01_01_000000_create_users_table.php
// Remplace complètement la migration Laravel de base

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {

            // ── Clé primaire ──────────────────────────────────────────
            $table->id();

            // ── Identité ──────────────────────────────────────────────
            $table->string('name');                          // prénom + nom (Laravel Auth)
            $table->string('prenom')->nullable();
            $table->string('nom')->nullable();
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');

            // ── Téléphone ─────────────────────────────────────────────
            $table->string('telephone')->nullable();
            $table->string('tel_indicatif')->nullable();     // +261, +33…
            $table->string('whatsapp')->nullable();          // WhatsApp recommandé

            // ── Adresse ───────────────────────────────────────────────
            $table->string('adresse_complete')->nullable();
            $table->string('rue')->nullable();
            $table->string('ville')->nullable();
            $table->string('region')->nullable();
            $table->string('codepostal')->nullable();
            $table->string('pays', 2)->nullable();           // ISO 3166-1 alpha-2

            // ── Traversée choisie ─────────────────────────────────────
            $table->string('traversee')->nullable();         // regard | presence | absolu
            $table->string('traversee_label')->nullable();   // Regard | Présence | Absolu
            $table->string('traversee_prix')->nullable();    // 800 € | 1 400 € | …

            // ── Paiement ─────────────────────────────────────────────
            // stripe_id, pm_type, pm_last_four, trial_ends_at
            // sont ajoutés par Cashier (vendor:publish --tag="cashier-migrations")
            $table->enum('methode', ['stripe', 'paypal'])->nullable();
            $table->enum('fraction', ['comptant', '2x', '3x', 'acompte'])->default('comptant');
            $table->enum('statut', ['pending', 'paye', 'annule', 'rembourse'])->default('pending');
            $table->string('paypal_order_id')->nullable();
            $table->timestamp('paid_at')->nullable();

            // ── Préférences & métadonnées ─────────────────────────────
            $table->string('source')->nullable();
            $table->string('timezone')->nullable();
            $table->string('locale', 10)->nullable();
            $table->string('theme_preference')->nullable();  // night | dawn | noon
            $table->string('registered_at_client')->nullable();

            // ── Pacte de l'Aman ───────────────────────────────────────
            $table->boolean('pacte_aman_accepted')->default(false);
            $table->timestamp('pacte_aman_at')->nullable();

            // ── Intégration EspoCRM ───────────────────────────────────
            $table->string('espo_lead_id')->nullable();

            // ── Rôle ─────────────────────────────────────────────────
            $table->string('role')->default('nomade');       // nomade | guide | admin

            // ── Laravel standard ──────────────────────────────────────
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();                           // deleted_at (SoftDeletes)

            // ── Index ─────────────────────────────────────────────────
            $table->index('statut');
            $table->index('traversee');
            $table->index('methode');
            $table->index('role');
        });

        // ── Tables Laravel standard ───────────────────────────────────
        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sessions');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('users');
    }
};