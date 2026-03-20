<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable // implements MustVerifyEmail  ← décommenter pour vérif. email
{
    use HasFactory, Notifiable, SoftDeletes;

    // ─────────────────────────────────────────────────────────────────
    //  CHAMPS ASSIGNABLES EN MASSE
    // ─────────────────────────────────────────────────────────────────

    protected $fillable = [
        // ── Identité ─────────────────────────────────────────────────
        'prenom',
        'nom',
        'name',               // prénom + nom (colonne standard Laravel Auth)
        'email',

        // ── Téléphone ────────────────────────────────────────────────
        'telephone',          // numéro complet (indicatif + numéro)
        'tel_indicatif',      // indicatif seul (+261, +33…)

        // ── Adresse ──────────────────────────────────────────────────
        'rue',
        'ville',
        'region',
        'codepostal',
        'pays',               // ISO 3166-1 alpha-2 (MG, FR…)
        'adresse_complete',   // adresse formatée sur une ligne (générée côté JS)

        // ── Traversée choisie ─────────────────────────────────────────
        'traversee',          // clé : regard | presence | absolu
        'traversee_label',    // libellé lisible : Regard / Présence / Absolu
        'traversee_prix',     // tarif affiché : 600 € / 1 000 € / Prix sur demande

        // ── Préférences & métadonnées ─────────────────────────────────
        'timezone',           // fuseau horaire détecté côté navigateur
        'locale',             // langue navigateur (ex : fr-FR)
        'theme_preference',   // night | dawn | noon
        'source',             // utm_source ou document.referrer
        'registered_at_client', // timestamp ISO côté navigateur

        // ── Pacte de l'Aman ───────────────────────────────────────────
        'pacte_aman_accepted',
        'pacte_aman_at',

        // ── Intégration EspoCRM ───────────────────────────────────────
        'espo_lead_id',       // ID du Lead créé dans EspoCRM

        // ── Rôle & sécurité ───────────────────────────────────────────
        'role',               // nomade | guide | admin
        'password',
    ];

    // ─────────────────────────────────────────────────────────────────
    //  CHAMPS CACHÉS (jamais sérialisés en JSON)
    // ─────────────────────────────────────────────────────────────────

    protected $hidden = [
        'password',
        'remember_token',
    ];

    // ─────────────────────────────────────────────────────────────────
    //  CASTS AUTOMATIQUES
    // ─────────────────────────────────────────────────────────────────

    protected function casts(): array
    {
        return [
            'email_verified_at'   => 'datetime',
            'pacte_aman_accepted' => 'boolean',
            'pacte_aman_at'       => 'datetime',
            'password'            => 'hashed',
            'created_at'          => 'datetime',
            'updated_at'          => 'datetime',
            'deleted_at'          => 'datetime',
        ];
    }

    // ─────────────────────────────────────────────────────────────────
    //  CONSTANTES — CATALOGUE DES TRAVERSÉES
    //  Source de vérité partagée avec RegisteredUserController.
    // ─────────────────────────────────────────────────────────────────

    public const TRAVERSEES = [
        'regard' => [
            'label' => 'Regard',
            'prix'  => '600 €',
            'tag'   => 'Se poser, cesser de fuir et enfin se voir.',
            'feats' => '3 Dialogues à cœur ouvert|Le Lien — fil direct Sentinelle|Le Journal de Bord',
            'sens'  => 'Passer de l\'aveuglement à la clarté.',
        ],
        'presence' => [
            'label' => 'Présence',
            'prix'  => '1 000 €',
            'tag'   => 'Habiter son corps, ses sens et chaque instant.',
            'feats' => '6 Dialogues profonds|L\'Oasis — espace Nomades|L\'Ancrage — rituels sensoriels',
            'sens'  => 'Ne plus subir, mais habiter pleinement.',
        ],
        'absolu' => [
            'label' => 'Absolu',
            'prix'  => 'Prix sur demande',
            'tag'   => 'Le dépouillement total pour la renaissance ultime.',
            'feats' => 'L\'Appel du Désert — Djanet|Le Silence — 7 jours d\'immersion|La Mue — accompagnement quotidien',
            'sens'  => 'Revenir transformé à jamais.',
        ],
    ];

    // ─────────────────────────────────────────────────────────────────
    //  ACCESSEURS
    // ─────────────────────────────────────────────────────────────────

    /**
     * Nom d'affichage complet : Prénom NOM.
     */
    public function getFullNameAttribute(): string
    {
        return trim($this->prenom . ' ' . $this->nom);
    }

    /**
     * Initiales (ex : "AN" pour Andry Nomade).
     */
    public function getInitialsAttribute(): string
    {
        return mb_strtoupper(
            mb_substr($this->prenom ?? '', 0, 1) .
            mb_substr($this->nom    ?? '', 0, 1)
        );
    }

    /**
     * Données complètes de la traversée choisie depuis le catalogue.
     * Retourne null si aucune traversée n'est définie.
     */
    public function getTraverseeDataAttribute(): ?array
    {
        return self::TRAVERSEES[$this->traversee] ?? null;
    }

    /**
     * Indique si le lead a déjà été envoyé à EspoCRM.
     */
    public function getHasEspoLeadAttribute(): bool
    {
        return ! empty($this->espo_lead_id);
    }

    /**
     * Retourne true si l'utilisateur est admin.
     */
    public function getIsAdminAttribute(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Retourne true si l'utilisateur est guide.
     */
    public function getIsGuideAttribute(): bool
    {
        return $this->role === 'guide';
    }

    /**
     * Retourne true si l'utilisateur est un simple Nomade.
     */
    public function getIsNomadeAttribute(): bool
    {
        return $this->role === 'nomade';
    }

    // ─────────────────────────────────────────────────────────────────
    //  SCOPES
    // ─────────────────────────────────────────────────────────────────

    /** Filtre les Nomades (rôle = nomade). */
    public function scopeNomades($query)
    {
        return $query->where('role', 'nomade');
    }

    /** Filtre les Guides. */
    public function scopeGuides($query)
    {
        return $query->where('role', 'guide');
    }

    /** Filtre par pays (ISO 3166-1 alpha-2). */
    public function scopeByCountry($query, string $countryCode)
    {
        return $query->where('pays', strtoupper($countryCode));
    }

    /** Filtre les utilisateurs ayant accepté le Pacte de l'Aman. */
    public function scopePacteAccepted($query)
    {
        return $query->where('pacte_aman_accepted', true);
    }

    /** Filtre par traversée choisie. */
    public function scopeByTraversee($query, string $traversee)
    {
        return $query->where('traversee', $traversee);
    }

    /** Filtre les utilisateurs déjà synchronisés avec EspoCRM. */
    public function scopeWithEspoLead($query)
    {
        return $query->whereNotNull('espo_lead_id');
    }

    /** Filtre les utilisateurs à synchroniser avec EspoCRM (pas encore de lead). */
    public function scopeWithoutEspoLead($query)
    {
        return $query->whereNull('espo_lead_id');
    }
}