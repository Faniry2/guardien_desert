<?php
// ═══════════════════════════════════════════════════════════════
// app/Livewire/Carnet/Setup.php
// ═══════════════════════════════════════════════════════════════
namespace App\Livewire\Carnet;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class Setup extends Component
{
    public function mount(): void
    {
        // Si déjà configuré, rediriger
        if (Auth::user()->encryptionKey?->is_configured) {
            $this->redirect(route('carnet.index'));
        }
    }

    public function render()
    {
        return view('livewire.carnet.setup')
            ->layout('layouts.app', ['title' => 'Configuration du carnet']);
    }
}
