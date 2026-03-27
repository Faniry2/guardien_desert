<?php
// ═══════════════════════════════════════════════════════════════
// app/Livewire/Carnet/LockButton.php
// ═══════════════════════════════════════════════════════════════
namespace App\Livewire\Carnet;

use Livewire\Component;

class LockButton extends Component
{
    public function lock(): void
    {
        session()->forget('journal_unlocked');
        // Le JS effacera le sessionStorage via un event
        $this->dispatch('journal-locked');
    }

    public function render()
    {
        return view('livewire.carnet.lock-button');
    }
}