<?php

// ═══════════════════════════════════════════════════════════════
// app/Models/UserEncryptionKey.php
// ═══════════════════════════════════════════════════════════════

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserEncryptionKey extends Model
{
    protected $fillable = [
        'user_id',
        'salt_hex',         // Salt PBKDF2 — NON secret, stocké en clair
        'key_check_hash',   // HMAC de vérification — pas la clé
        'hint',             // Indice optionnel (en clair)
        'is_configured',
        'configured_at',
    ];

    protected $casts = [
        'is_configured' => 'boolean',
        'configured_at' => 'datetime',
    ];

    // La clé AES n'est JAMAIS stockée ici.

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
