<?php



// ═══════════════════════════════════════════════════════════════
// app/Livewire/Detente/MiniPlayer.php
// ═══════════════════════════════════════════════════════════════
namespace App\Livewire\Detente;

use Livewire\Component;
use App\Models\RelaxationSession;
use Illuminate\Support\Facades\Auth;

class MiniPlayer extends Component
{
    public function logSession(string $slug, int $duration): void
    {
        RelaxationSession::create([
            'user_id'          => Auth::id(),
            'track_slug'       => $slug,
            'duration_seconds' => $duration,
            'listened_at'      => now(),
        ]);
    }

    public function render()
    {
        return view('livewire.detente.mini-player');
    }
}
