<?php

// app/Livewire/Dashboard.php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class Dashboard extends Component
{
    public int    $dayNumber   = 0;
    public int    $module      = 1;
    public int    $progress    = 0;
    public array  $recentEntries = [];

    public function mount(): void
    {
        $user   = Auth::user();
        $carnet = $user->carnet;

        if ($carnet) {
            $this->dayNumber = $carnet->currentDayNumber();
            $this->module    = (int) ceil($this->dayNumber / 10);
            $this->progress  = $carnet->progress_percentage;

            $this->recentEntries = $carnet
                ->entries()
                ->where('is_completed', true)
                ->orderByDesc('day_number')
                ->limit(5)
                ->get()
                ->toArray();
        }
    }

    public function render()
    {
        return view('livewire.dashboard')
            ->layout('layouts.app', ['title' => 'Tableau de bord']);
    }
}
