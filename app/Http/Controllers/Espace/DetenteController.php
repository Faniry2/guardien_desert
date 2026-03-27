<?php
//═══════════════════════════════════════════════════════════════
// app/Http/Controllers/Espace/DetenteController.php
// ═══════════════════════════════════════════════════════════════
namespace App\Http\Controllers\Espace;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class DetenteController extends Controller
{
    public function index(): View
    {
        return view('espace.detente.index', [
            'tracks' => config('relaxation_tracks'),
            'meditations' => config('meditations'),
        ]);
    }

    public function musique(): View
    {
        return view('espace.detente.musique', [
            'tracks' => config('relaxation_tracks'),
        ]);
    }

    public function stream(string $slug): mixed
    {
        $allowed = collect(config('relaxation_tracks'))->pluck('slug')->toArray();
        abort_unless(in_array($slug, $allowed), 404);

        $path = storage_path("app/audio/{$slug}.mp3");
        abort_unless(file_exists($path), 404);

        return response()->file($path, [
            'Content-Type'  => 'audio/mpeg',
            'Cache-Control' => 'no-store',
            'Accept-Ranges' => 'bytes',
        ]);
    }

    public function meditations(): View
    {
        return view('espace.detente.meditations', [
            'meditations' => config('meditations'),
        ]);
    }
}
