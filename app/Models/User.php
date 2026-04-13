<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Cashier\Billable;          // ← CASHIER OBLIGATOIRE
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;
    use Billable; // ← TRAIT CASHIER — donne accès à checkout(), newSubscription(), etc.

    // ─────────────────────────────────────────────────────────────────
    //  CHAMPS ASSIGNABLES EN MASSE
    // ─────────────────────────────────────────────────────────────────
    protected $fillable = [
        // ── Identité ──────────────────────────────────────────────────
        'prenom', 'nom', 'name', 'email',

        // ── Téléphone ─────────────────────────────────────────────────
        'telephone', 'tel_indicatif',
        'whatsapp',           // contact WhatsApp (recommandé)

        // ── Adresse ───────────────────────────────────────────────────
        'rue', 'ville', 'region', 'codepostal', 'pays', 'adresse_complete',

        // ── Traversée choisie ─────────────────────────────────────────
        'traversee',          // regard | presence | absolu
        'traversee_label',
        'traversee_prix',

        // ── Paiement ──────────────────────────────────────────────────
        // NB: stripe_id, pm_type, pm_last_four, trial_ends_at
        //     sont gérés automatiquement par Cashier via sa migration
        'methode',            // stripe | paypal
        'fraction',           // comptant | 2x | 3x | acompte
        'statut',             // pending | paye | annule | rembourse
        'paypal_order_id',    // géré manuellement (PayPal hors Cashier)
        'paid_at',

        // ── Préférences & métadonnées ─────────────────────────────────
        'source', 'timezone', 'locale',
        'theme_preference', 'registered_at_client',

        // ── Pacte de l'Aman ───────────────────────────────────────────
        'pacte_aman_accepted', 'pacte_aman_at',

        // ── Intégration EspoCRM ───────────────────────────────────────
        'espo_lead_id',

        // ── Rôle & sécurité ───────────────────────────────────────────
        'role', 'password',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return [
            'email_verified_at'   => 'datetime',
            'pacte_aman_accepted' => 'boolean',
            'pacte_aman_at'       => 'datetime',
            'paid_at'             => 'datetime',
            'password'            => 'hashed',
            'created_at'          => 'datetime',
            'updated_at'          => 'datetime',
            'deleted_at'          => 'datetime',
        ];
    }

    // ─────────────────────────────────────────────────────────────────
    //  CASHIER — Surcharge du nom/email envoyés à Stripe
    //  (doc : https://laravel.com/docs/12.x/billing#syncing-customer-data-with-stripe)
    // ─────────────────────────────────────────────────────────────────

    public function stripeName(): string|null
    {
        return $this->prenom . ' ' . $this->nom;
    }

    public function stripeEmail(): string|null
    {
        return $this->email;
    }

    public function stripePhone(): string|null
    {
        return $this->telephone;
    }

    // ─────────────────────────────────────────────────────────────────
    //  CONSTANTES — CATALOGUE DES TRAVERSÉES
    // ─────────────────────────────────────────────────────────────────

    public const TRAVERSEES = [
        'regard' => [
            'label'        => 'Regard',
            'prix'         => '800 €',
            'montant'      => 800,
            'stripe_price' => 'price_REGARD_800',   // ← remplacer par ton vrai Price ID
            'tag'          => 'Se poser, cesser de fuir et enfin se voir.',
            'sens'         => 'Passer de l\'aveuglement à la clarté.',
        ],
        'presence' => [
            'label'        => 'Présence',
            'prix'         => '1 400 €',
            'montant'      => 1400,
            'stripe_price' => 'price_PRESENCE_1400', // ← remplacer
            'tag'          => 'Habiter son corps, ses sens et chaque instant.',
            'sens'         => 'Ne plus subir, mais habiter pleinement.',
        ],
        'absolu' => [
            'label'        => 'Absolu',
            'prix'         => 'Prix sur demande',
            'montant'      => 4000,
            'stripe_price' => 'price_ABSOLU_4000',   // ← remplacer
            'tag'          => 'Le dépouillement total pour la renaissance ultime.',
            'sens'         => 'Revenir transformé à jamais.',
        ],
    ];

    public const METHODES  = ['stripe', 'paypal'];
    public const FRACTIONS = ['comptant', '2x', '3x', 'acompte'];
    public const STATUTS   = ['pending', 'paye', 'annule', 'rembourse'];

    // ─────────────────────────────────────────────────────────────────
    //  ACCESSEURS — IDENTITÉ
    // ─────────────────────────────────────────────────────────────────

    public function getFullNameAttribute(): string
    {
        return trim($this->prenom . ' ' . $this->nom);
    }

    public function getInitialsAttribute(): string
    {
        return mb_strtoupper(
            mb_substr($this->prenom ?? '', 0, 1) .
            mb_substr($this->nom    ?? '', 0, 1)
        );
    }

    public function firstName(): string
    {
        return $this->prenom ?? explode(' ', $this->name)[0];
    }

    // ─────────────────────────────────────────────────────────────────
    //  ACCESSEURS — TRAVERSÉE
    // ─────────────────────────────────────────────────────────────────

    public function getTraverseeDataAttribute(): ?array
    {
        return self::TRAVERSEES[$this->traversee] ?? null;
    }

    public function getLibelleTraverseeAttribute(): string
    {
        return match($this->traversee) {
            'regard'   => 'Renaît-Sens Essentiel — Regard',
            'presence' => 'Renaît-Sens Avancé — Présence',
            'absolu'   => 'Renaît-Sens Premium — Absolu',
            default    => 'Traversée inconnue',
        };
    }

    public function getMontantAttribute(): int
    {
        return self::TRAVERSEES[$this->traversee]['montant'] ?? 0;
    }

    // ─────────────────────────────────────────────────────────────────
    //  ACCESSEURS — PAIEMENT
    // ─────────────────────────────────────────────────────────────────

    public function getIsPayeAttribute(): bool
    {
        return $this->statut === 'paye';
    }

    public function getHasCarnetAccessAttribute(): bool
    {
        return $this->statut === 'paye';
    }

    public function getFractionLabelAttribute(): string
    {
        return match($this->fraction) {
            '2x'      => '2 fois — 2 × ' . intval($this->montant / 2) . ' €',
            '3x'      => '3 fois — 3 × ' . intval($this->montant / 3) . ' €',
            'acompte' => 'Acompte 1 000 € + 3 × 1 000 €',
            default   => $this->montant . ' € comptant',
        };
    }

    // ─────────────────────────────────────────────────────────────────
    //  ACCESSEURS — RÔLE
    // ─────────────────────────────────────────────────────────────────

    public function getIsAdminAttribute(): bool  { return $this->role === 'admin'; }
    public function getIsGuideAttribute(): bool  { return $this->role === 'guide'; }
    public function getIsNomadeAttribute(): bool { return $this->role === 'nomade'; }
    public function getHasEspoLeadAttribute(): bool { return ! empty($this->espo_lead_id); }

    // ─────────────────────────────────────────────────────────────────
    //  SCOPES
    // ─────────────────────────────────────────────────────────────────

    public function scopeNomades($q)  { return $q->where('role', 'nomade'); }
    public function scopeGuides($q)   { return $q->where('role', 'guide'); }
    public function scopeAdmins($q)   { return $q->where('role', 'admin'); }
    public function scopePaye($q)     { return $q->where('statut', 'paye'); }
    public function scopePending($q)  { return $q->where('statut', 'pending'); }
    public function scopeAnnule($q)   { return $q->where('statut', 'annule'); }

    public function scopeByTraversee($q, string $t) { return $q->where('traversee', $t); }
    public function scopeByMethode($q, string $m)   { return $q->where('methode', $m); }
    public function scopeByCountry($q, string $c)   { return $q->where('pays', strtoupper($c)); }
    public function scopePacteAccepted($q)          { return $q->where('pacte_aman_accepted', true); }
    public function scopeWithEspoLead($q)    { return $q->whereNotNull('espo_lead_id'); }
    public function scopeWithoutEspoLead($q) { return $q->whereNull('espo_lead_id'); }

    // ─────────────────────────────────────────────────────────────────
    //  RELATIONS
    // ─────────────────────────────────────────────────────────────────

    public function encryptionKey()      { return $this->hasOne(UserEncryptionKey::class); }
    public function carnet()             { return $this->hasOne(Carnet::class); }
    public function relaxationSessions() { return $this->hasMany(RelaxationSession::class); }
}