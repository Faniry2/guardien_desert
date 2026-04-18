<?php

// ═══════════════════════════════════════════════════════════════
// app/Http/Controllers/Api/EncryptionKeyController.php
// ═══════════════════════════════════════════════════════════════

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\UserEncryptionKey;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class EncryptionKeyController extends Controller
{
    /**
     * Retourne le salt + key_check_hash pour la dérivation côté JS.
     * Ces données ne sont PAS secrètes — le salt est public par design PBKDF2.
     */
    public function getSalt(Request $request): JsonResponse
    {
        $key = $request->user()->encryptionKey;

        if (! $key || ! $key->is_configured) {
            return response()->json(['configured' => false]);
        }

        return response()->json([
            'configured'      => true,
            'salt_hex'        => $key->salt_hex,
            'key_check_hash'  => $key->key_check_hash,
            'hint'            => $key->hint,
        ]);
    }

    /**
     * Configure la clé de chiffrement pour la première fois.
     * Reçoit le salt + key_check_hash — JAMAIS la clé elle-même.
     */
        public function setup(Request $request): JsonResponse
    {
     

        $validated = $request->validate([
            'salt_hex'       => ['required', 'string', 'size:64', 'regex:/^[a-f0-9]+$/'],
            'key_check_hash' => ['required', 'string', 'max:512'],
            'hint'           => ['nullable', 'string', 'max:255'],
        ]);


        // Vérifier que la clé n'est pas déjà configurée
        if ($request->user()->encryptionKey?->is_configured) {
            \Log::info('Clé déjà configurée');
            return response()->json(['error' => 'Clé déjà configurée'], 409);
        }

        try {
            $request->user()->encryptionKey()->updateOrCreate(
                ['user_id' => $request->user()->id],
                array_merge($validated, [
                    'is_configured' => true,
                    'configured_at' => now(),
                ])
            );
            

            $request->user()->carnet()->firstOrCreate([
                'user_id' => $request->user()->id,
            ], [
                'start_date' => today(),
                'status'     => 'active',
            ]);
            

        } catch (\Exception $e) {
           
            return response()->json(['error' => $e->getMessage()], 500);
        }

        return response()->json(['success' => true]);
    }
}
