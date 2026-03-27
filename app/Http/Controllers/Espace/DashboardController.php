<?php
// ═══════════════════════════════════════════════════════════════
// app/Http/Controllers/Espace/DashboardController.php
// ═══════════════════════════════════════════════════════════════
namespace App\Http\Controllers\Espace;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(Request $request): View
    {
        $user   = $request->user();
        $carnet = $user->carnet;

        return view('espace.dashboard', [
            'carnet'        => $carnet,
            'dayNumber'     => $carnet ? $carnet->currentDayNumber() : 0,
            'module'        => $carnet ? (int) ceil($carnet->currentDayNumber() / 10) : 1,
            'progress'      => $carnet ? $carnet->progress_percentage : 0,
            'recentEntries' => $carnet
                ? $carnet->entries()->where('is_completed', true)->orderByDesc('day_number')->limit(5)->get()
                : collect(),
        ]);
    }
}