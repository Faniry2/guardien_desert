<?php



// ═══════════════════════════════════════════════════════════════
// app/Livewire/Carnet/Bilans.php
// ═══════════════════════════════════════════════════════════════
namespace App\Livewire\Carnet;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class Bilans extends Component
{
    public int   $currentDay    = 0;
    public array $moduleReviews = [];

    public function mount(): void
    {
        $carnet = Auth::user()->carnet;
        if (!$carnet) { $this->redirect(route('carnet.setup')); return; }

        $this->currentDay = $carnet->currentDayNumber();
        $this->moduleReviews = $carnet->moduleReviews()
            ->get()->keyBy('module_number')->toArray();
    }

    public function render()
    {
        return view('livewire.carnet.bilans')
            ->layout('layouts.app', ['title' => 'Bilans']);
    }
}