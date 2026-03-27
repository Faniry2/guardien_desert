<?php

// ═══════════════════════════════════════════════════════════════
// app/Livewire/Carnet/Index.php
// ═══════════════════════════════════════════════════════════════
namespace App\Livewire\Carnet;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class Index extends Component
{
    public int   $currentDay = 0;
    public array $entries    = [];
    public array $completedModules = [];

    public function mount(): void
    {
        $user   = Auth::user();
        $carnet = $user->carnet;

        if (!$carnet) {
            $this->redirect(route('carnet.setup'));
            return;
        }

        $this->currentDay = $carnet->currentDayNumber();

        $this->entries = $carnet->entries()
            ->get()
            ->keyBy('day_number')
            ->toArray();

        // Modules avec bilan complété
        foreach ($carnet->moduleReviews()->where('is_completed', true)->get() as $r) {
            $this->completedModules[$r->module_number] = true;
        }
    }

    public function render()
    {
        return view('livewire.carnet.index')
            ->layout('layouts.app', ['title' => 'Mon Carnet']);
    }
}