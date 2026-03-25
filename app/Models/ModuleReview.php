<?php
// ═══════════════════════════════════════════════════════════════
// app/Models/ModuleReview.php
// ═══════════════════════════════════════════════════════════════

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ModuleReview extends Model
{
    protected $fillable = [
        'carnet_id',
        'module_number',            // EN CLAIR
        'synthesis_encrypted',      // CHIFFRÉ
        'learnings_encrypted',      // CHIFFRÉ
        'intentions_encrypted',     // CHIFFRÉ
        'is_completed',             // EN CLAIR
    ];

    protected $casts = ['is_completed' => 'boolean'];

    public function carnet()
    {
        return $this->belongsTo(Carnet::class);
    }
}


// ═══════════════════════════════════════════════════════════════
// app/Models/FinalReview.php
// ═══════════════════════════════════════════════════════════════

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FinalReview extends Model
{
    protected $fillable = [
        'carnet_id',
        'transformation_encrypted', // CHIFFRÉ
        'gratitude_encrypted',      // CHIFFRÉ
        'letter_to_self_encrypted', // CHIFFRÉ
        'next_chapter_encrypted',   // CHIFFRÉ
        'is_completed',
    ];

    protected $casts = ['is_completed' => 'boolean'];

    public function carnet()
    {
        return $this->belongsTo(Carnet::class);
    }
}