<?php

// ═══════════════════════════════════════════════════════════════
// app/Http/Controllers/Api/AudioController.php
// ═══════════════════════════════════════════════════════════════

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\RelaxationSession;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class AudioController extends Controller
{
    private const ALLOWED_TRACKS = [
        'foret-matinale', 'bol-tibetain', 'pluie-douce',
        'ocean-respiration', 'cristaux-sonores', 'vent-sacre',
    ];

    /**
     * Stream le fichier audio (protégé — auth requise).
     * Les fichiers ne sont JAMAIS accessibles publiquement.
     */
    public function stream(Request $request, string $slug): StreamedResponse
    {
        abort_unless(in_array($slug, self::ALLOWED_TRACKS), 404);

        $path = "audio/{$slug}.mp3";
        abort_unless(Storage::exists($path), 404);

        // Logger la session d'écoute
        RelaxationSession::create([
            'user_id'    => $request->user()->id,
            'track_slug' => $slug,
            'listened_at'=> now(),
        ]);

        return Storage::response($path, null, [
            'Content-Type'  => 'audio/mpeg',
            'Cache-Control' => 'no-store',
            'Accept-Ranges' => 'bytes',
        ]);
    }
}
