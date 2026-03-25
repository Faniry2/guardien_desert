<?php

// ════════════════════════════════════════════════════════════════
// app/Http/Controllers/Api/CarnetEntryController.php
// ═══════════════════════════════════════════════════════════════

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CarnetEntry;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CarnetEntryController extends Controller
{
    /**
     * Retourne les blobs chiffrés pour un jour donné.
     * Le serveur ne voit que du texte chiffré opaque.
     */
    public function show(Request $request, int $day): JsonResponse
    {
        $carnet = $request->user()->carnet;
        abort_unless($carnet, 404, 'Carnet introuvable');
        abort_unless($day >= 1 && $day <= 90, 422, 'Jour invalide');

        $entry = $carnet->entries()->where('day_number', $day)->first();
        if (! $entry) {
            return response()->json(['day_number' => $day, 'exists' => false]);
        }

        return response()->json([
            'day_number'            => $entry->day_number,
            'exists'                => true,
            'is_completed'          => $entry->is_completed,
            'title_encrypted'       => $entry->title_encrypted,
            'color_encrypted'       => $entry->color_encrypted,
            'mood_encrypted'        => $entry->mood_encrypted,
            'highlight_encrypted'   => $entry->highlight_encrypted,
            'reflection_encrypted'  => $entry->reflection_encrypted,
            'questions_encrypted'   => $entry->questions_encrypted,
            'free_writing_encrypted'=> $entry->free_writing_encrypted,
            'awareness_encrypted'   => $entry->awareness_encrypted,
            'commitment_encrypted'  => $entry->commitment_encrypted,
        ]);
    }

    /**
     * Sauvegarde les blobs chiffrés pour un jour.
     * Aucun champ en clair n'est accepté ici (sauf is_completed).
     */
    public function update(Request $request, int $day): JsonResponse
    {
        $carnet = $request->user()->carnet;
        abort_unless($carnet, 404);

        // Validation : uniquement des blobs base64 opaques
        $validated = $request->validate([
            'title_encrypted'        => ['nullable', 'string', 'max:65535'],
            'color_encrypted'        => ['nullable', 'string', 'max:65535'],
            'mood_encrypted'         => ['nullable', 'string', 'max:65535'],
            'highlight_encrypted'    => ['nullable', 'string', 'max:65535'],
            'reflection_encrypted'   => ['nullable', 'string', 'max:65535'],
            'questions_encrypted'    => ['nullable', 'string', 'max:65535'],
            'free_writing_encrypted' => ['nullable', 'string', 'max:65535'],
            'awareness_encrypted'    => ['nullable', 'string', 'max:65535'],
            'commitment_encrypted'   => ['nullable', 'string', 'max:65535'],
            'is_completed'           => ['nullable', 'boolean'],
        ]);

        $entry = CarnetEntry::updateOrCreate(
            ['carnet_id' => $carnet->id, 'day_number' => $day],
            array_merge($validated, [
                'entry_date' => $carnet->start_date->addDays($day - 1),
            ])
        );

        return response()->json(['success' => true, 'id' => $entry->id]);
    }
}
