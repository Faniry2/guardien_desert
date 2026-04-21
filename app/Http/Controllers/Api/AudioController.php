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
    // private const ALLOWED_TRACKS = [
    //     'foret-matinale', 'bol-tibetain', 'pluie-douce',
    //     'ocean-respiration', 'cristaux-sonores', 'vent-sacre',
    // ];

    /**
     * Stream le fichier audio (protégé — auth requise).
     * Les fichiers ne sont JAMAIS accessibles publiquement.
     */
    public function stream(Request $request, string $slug): StreamedResponse
    {
        $path = storage_path('app/audio/' . $slug . '.mp3');

        abort_unless(file_exists($path), 404);

        RelaxationSession::create([
            'user_id'     => $request->user()->id,
            'track_slug'  => $slug,
            'listened_at' => now(),
        ]);

        $size = filesize($path);

        // Support Range pour le seek Howler
        if (request()->hasHeader('Range')) {
            $range  = request()->header('Range');
            preg_match('/bytes=(\d+)-(\d*)/', $range, $matches);
            $start  = intval($matches[1]);
            $end    = isset($matches[2]) && $matches[2] !== '' ? intval($matches[2]) : $size - 1;
            $length = $end - $start + 1;

            return response()->stream(function () use ($path, $start, $length) {
                $handle = fopen($path, 'rb');
                fseek($handle, $start);
                $remaining = $length;
                while (!feof($handle) && $remaining > 0) {
                    $chunk = fread($handle, min(8192, $remaining));
                    echo $chunk;
                    $remaining -= strlen($chunk);
                    ob_flush(); flush();
                }
                fclose($handle);
            }, 206, [
                'Content-Type'   => 'audio/mpeg',
                'Content-Range'  => "bytes {$start}-{$end}/{$size}",
                'Content-Length' => $length,
                'Accept-Ranges'  => 'bytes',
                'Cache-Control'  => 'no-store',
            ]);
        }

        return response()->stream(function () use ($path) {
            $handle = fopen($path, 'rb');
            while (!feof($handle)) {
                echo fread($handle, 8192);
                ob_flush(); flush();
            }
            fclose($handle);
        }, 200, [
            'Content-Type'   => 'audio/mpeg',
            'Content-Length' => $size,
            'Accept-Ranges'  => 'bytes',
            'Cache-Control'  => 'no-store',
        ]);
    }
}
