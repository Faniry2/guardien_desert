<?php
// app/Http/Controllers/Espace/MusiqueController.php

namespace App\Http\Controllers\Espace;

use App\Http\Controllers\Controller;
use App\Models\Playlist;
use App\Models\Track;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class MusiqueController extends Controller
{
    // ── Page principale musique ───────────────────────────────────
    public function index()
    {
        // Playlists par ambiance
        $ambiancePlaylists = Playlist::active()
            ->ambiance()
            ->with('tracks')
            ->orderBy('order')
            ->get();

        // Playlists par module
        $modulePlaylists = Playlist::active()
            ->module()
            ->with('tracks')
            ->orderBy('order')
            ->get();

        // Toutes les pistes pour la vue "toutes les pistes"
        $allTracks = Track::active()->orderBy('order')->get();

        return view('espace.detente.musique', compact(
            'ambiancePlaylists',
            'modulePlaylists',
            'allTracks'
        ));
    }

    // ── Streaming audio ───────────────────────────────────────────
    public function stream(string $slug): StreamedResponse
    {
        $track = Track::where('slug', $slug)->where('is_active', true)->firstOrFail();
        $path  = storage_path('app/audio/' . $track->filename);

        abort_if(! file_exists($path), 404, 'Fichier audio introuvable');

        $size    = filesize($path);
        $mime    = 'audio/mpeg';
        $headers = ['Content-Type' => $mime, 'Accept-Ranges' => 'bytes'];

        // ── Support du Range (seek dans le player) ─────────────────
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
                while (! feof($handle) && $remaining > 0) {
                    $chunk = fread($handle, min(8192, $remaining));
                    echo $chunk;
                    $remaining -= strlen($chunk);
                    ob_flush();
                    flush();
                }
                fclose($handle);
            }, 206, array_merge($headers, [
                'Content-Range'  => "bytes {$start}-{$end}/{$size}",
                'Content-Length' => $length,
            ]));
        }

        // ── Réponse complète ──────────────────────────────────────
        return response()->stream(function () use ($path) {
            $handle = fopen($path, 'rb');
            while (! feof($handle)) {
                echo fread($handle, 8192);
                ob_flush();
                flush();
            }
            fclose($handle);
        }, 200, array_merge($headers, ['Content-Length' => $size]));
    }
}