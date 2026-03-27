<?php

// ═══════════════════════════════════════════════════════════════
// app/Models/Carnet.php
// ═══════════════════════════════════════════════════════════════

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Carnet extends Model
{
    protected $fillable = [
        'user_id',
        'intention_encrypted',   // CHIFFRÉ
        'couverture_encrypted',  // CHIFFRÉ
        'regles_encrypted',      // CHIFFRÉ
        'start_date',            // EN CLAIR (navigation)
        'status',
    ];

    protected $casts = [
        'start_date' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function entries()
    {
        return $this->hasMany(CarnetEntry::class)->orderBy('day_number');
    }

    public function moduleReviews()
    {
        return $this->hasMany(ModuleReview::class)->orderBy('module_number');
    }

    public function finalReview()
    {
        return $this->hasOne(FinalReview::class);
    }

    /** Jour actuel basé sur la date de début */
    public function currentDayNumber(): int
    {
        return min(90, max(1, $this->start_date->diffInDays(today()) + 1));
    }

    /** % de progression (basé sur is_completed, pas sur le contenu) */
    public function getProgressPercentageAttribute(): int
    {
        $completed = $this->entries()->where('is_completed', true)->count();
        return (int) round(($completed / 90) * 100);
    }

    /** Vérifie si un bilan de module est en attente */
    public function pendingModuleReview(): bool
    {
        $day    = $this->currentDayNumber();
        $module = (int) floor(($day - 1) / 10);
        if ($module === 0) return false;

        return ! $this->moduleReviews()
            ->where('module_number', $module)
            ->where('is_completed', true)
            ->exists();
    }
}
