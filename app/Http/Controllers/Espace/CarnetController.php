<?php


// ═══════════════════════════════════════════════════════════════
// app/Http/Controllers/Espace/CarnetController.php
// ═══════════════════════════════════════════════════════════════
namespace App\Http\Controllers\Espace;

use App\Http\Controllers\Controller;
use App\Models\CarnetEntry;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CarnetController extends Controller
{
   public function index(Request $request): View|RedirectResponse
    {
        $user = $request->user();

        // Pas de clé configurée → setup
        if (!$user->encryptionKey?->is_configured) {
            return redirect()->route('carnet.setup');
        }

        $carnet = $user->carnet;

        // Clé OK mais pas de carnet → créer le carnet automatiquement
        if (!$carnet) {
            $carnet = $user->carnet()->create([
                'start_date' => today(),
                'status'     => 'active',
            ]);
        }

        return view('espace.carnet.index', [
            'carnet'     => $carnet,
            'currentDay' => $carnet->currentDayNumber(),
            'entries'    => $carnet->entries()->get()->keyBy('day_number'),
        ]);
    }

    public function setup(Request $request): View|RedirectResponse
    {
        if ($request->user()->encryptionKey?->is_configured) {
            return redirect()->route('carnet.index');
        }
        return view('espace.carnet.setup');
    }

    public function day(Request $request, int $day): View|RedirectResponse
    {
        $carnet = $request->user()->carnet;
        if (!$carnet) return redirect()->route('carnet.setup');

        abort_unless($day >= 1 && $day <= 90, 404);

        $entry = $carnet->entries()->where('day_number', $day)->first();

        $encryptedFields = $entry ? [
            'title_encrypted'        => $entry->title_encrypted,
            'color_encrypted'        => $entry->color_encrypted,
            'mood_encrypted'         => $entry->mood_encrypted,
            'highlight_encrypted'    => $entry->highlight_encrypted,
            'reflection_encrypted'   => $entry->reflection_encrypted,
            'free_writing_encrypted' => $entry->free_writing_encrypted,
            'awareness_encrypted'    => $entry->awareness_encrypted,
            'commitment_encrypted'   => $entry->commitment_encrypted,
        ] : [];

        return view('espace.carnet.day', [
            'dayNumber'       => $day,
            'isCompleted'     => $entry?->is_completed ?? false,
            'encryptedFields' => $encryptedFields,
            'hint'            => $request->user()->encryptionKey?->hint,
            'prevDay'         => $day > 1  ? $day - 1 : null,
            'nextDay'         => $day < 90 ? $day + 1 : null,
        ]);
    }

    public function complete(Request $request, int $day): RedirectResponse
    {
        $carnet = $request->user()->carnet;
        abort_unless($carnet, 404);

        CarnetEntry::updateOrCreate(
            ['carnet_id' => $carnet->id, 'day_number' => $day],
            ['is_completed' => true, 'entry_date' => $carnet->start_date->addDays($day - 1)]
        );

        return back()->with('success', 'Jour marqué comme complété.');
    }

    public function bilans(Request $request): View
    {
        $carnet = $request->user()->carnet;

        return view('espace.carnet.bilans', [
            'currentDay'    => $carnet ? $carnet->currentDayNumber() : 0,
            'moduleReviews' => $carnet
                ? $carnet->moduleReviews()->get()->keyBy('module_number')
                : collect(),
        ]);
    }

    public function reset(Request $request): RedirectResponse
    {
        $user = $request->user();

        if ($carnet = $user->carnet) {
            $carnet->entries()->delete();
            $carnet->moduleReviews()->delete();
            $carnet->finalReview()?->delete();
            $carnet->delete();
        }

        $user->encryptionKey()?->delete();
        session()->forget('journal_unlocked');

        return redirect()->route('carnet.setup')
            ->with('warning', 'Votre carnet a été réinitialisé.');
    }
}
