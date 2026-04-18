<?php
// ════════════════════════════════════════════════════════════════
//  app/Models/Playlist.php
// ════════════════════════════════════════════════════════════════

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Playlist extends Model
{
    protected $fillable = [
        'name', 'slug', 'description', 'type', 'category',
        'module', 'cover', 'order', 'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function tracks()
    {
        return $this->belongsToMany(Track::class, 'playlist_track')
                    ->withPivot('order')
                    ->orderBy('playlist_track.order')
                    ->where('tracks.is_active', true);
    }

    public function scopeActive($q)   { return $q->where('is_active', true); }
    public function scopeAmbiance($q) { return $q->where('type', 'ambiance'); }
    public function scopeModule($q)   { return $q->where('type', 'module'); }
}