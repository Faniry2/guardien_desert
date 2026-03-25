<?php
// ═══════════════════════════════════════════════════════════════
// app/Models/CarnetEntry.php
// ═══════════════════════════════════════════════════════════════

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CarnetEntry extends Model
{
    protected $fillable = [
        'carnet_id',
        'day_number',               // EN CLAIR
        'entry_date',               // EN CLAIR
        'title_encrypted',          // CHIFFRÉ
        'color_encrypted',          // CHIFFRÉ
        'mood_encrypted',           // CHIFFRÉ
        'highlight_encrypted',      // CHIFFRÉ
        'reflection_encrypted',     // CHIFFRÉ
        'questions_encrypted',      // CHIFFRÉ
        'free_writing_encrypted',   // CHIFFRÉ
        'awareness_encrypted',      // CHIFFRÉ
        'commitment_encrypted',     // CHIFFRÉ
        'is_completed',             // EN CLAIR (progression)
    ];

    protected $casts = [
        'entry_date'   => 'date',
        'is_completed' => 'boolean',
    ];

    public function carnet()
    {
        return $this->belongsTo(Carnet::class);
    }

    /**
     * NE PAS utiliser côté serveur — uniquement pour affichage non-sensible.
     * Le déchiffrement réel se fait côté JS.
     */
    public function decryptedTitle(): ?string
    {
        // Retourne null car le serveur ne peut pas déchiffrer.
        // Le titre est déchiffré côté JS uniquement.
        return null;
    }
}
