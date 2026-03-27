<?php

// app/Http/Controllers/CarnetController.php

namespace App\Http\Controllers;

use App\Models\Carnet;
use App\Models\CarnetEntry;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CarnetController extends Controller
{
    /**
     * Réinitialise le carnet de l'utilisateur.
     * ATTENTION : supprime TOUTES les entrées chiffrées + la clé.
     * Irréversible — confirmation côté Vue obligatoire.
     */
    public function reset(Request $request): RedirectResponse
    {
        $user = Auth::user();

        // Supprimer toutes les entrées chiffrées
        if ($carnet = $user->carnet) {
            $carnet->entries()->delete();
            $carnet->moduleReviews()->delete();
            $carnet->finalReview()?->delete();
            $carnet->delete();
        }

        // Supprimer la clé de chiffrement (salt + hash)
        $user->encryptionKey()?->delete();

        // Invalider la session déverrouillée
        session()->forget('journal_unlocked');

        return redirect()
            ->route('carnet.setup')
            ->with('warning', 'Votre carnet a été réinitialisé. Veuillez créer une nouvelle phrase secrète.');
    }
}
