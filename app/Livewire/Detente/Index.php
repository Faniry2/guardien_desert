<?php
// ═══════════════════════════════════════════════════════════════
// app/Livewire/Detente/Index.php
// ═══════════════════════════════════════════════════════════════
namespace App\Livewire\Detente;

use Livewire\Component;

class Index extends Component
{
    public array $tracks = [];

    public function mount(): void
    {
        $this->tracks = config('relaxation_tracks');
    }

    public function render()
    {
        return view('livewire.detente.index')
            ->layout('layouts.app', ['title' => 'Espace Détente']);
    }
}
