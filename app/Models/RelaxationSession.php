<?php
// ═══════════════════════════════════════════════════════════════
// app/Models/RelaxationSession.php
// ═══════════════════════════════════════════════════════════════

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RelaxationSession extends Model
{
    protected $fillable = [
        'user_id',
        'track_slug',        // EN CLAIR
        'duration_seconds',  // EN CLAIR
        'listened_at',       // EN CLAIR
    ];

    protected $casts = ['listened_at' => 'datetime'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
