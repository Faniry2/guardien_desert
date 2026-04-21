<?php
// ════════════════════════════════════════════════════════════════
//  app/Models/Track.php
// ════════════════════════════════════════════════════════════════

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Track extends Model
{
    protected $fillable = [
        'title', 'slug', 'filename', 'category', 'module',
        'duration_seconds', 'duration_label', 'description',
        'order', 'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // Chemin complet du fichier audio
    public function getPathAttribute(): string
    {
        return storage_path('app/audio/' . $this->filename);
    }

    // URL de streaming via notre route
    public function getStreamUrlAttribute(): string
    {
        return '/api/audio/' . $this->slug;
    }

    public function playlists()
    {
        return $this->belongsToMany(Playlist::class, 'playlist_track')
                    ->withPivot('order')
                    ->orderBy('playlist_track.order');
    }

    public function scopeActive($q) { return $q->where('is_active', true); }
    public function scopeByCategory($q, string $cat) { return $q->where('category', $cat); }
    public function scopeByModule($q, string $mod) { return $q->where('module', $mod); }
}
