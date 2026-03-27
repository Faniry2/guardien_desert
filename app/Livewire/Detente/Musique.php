<?php

// app/Livewire/Detente/Musique.php

namespace App\Livewire\Detente;

use Livewire\Component;
use App\Models\RelaxationSession;
use Illuminate\Support\Facades\Auth;

class Musique extends Component
{
    public array  $tracks       = [];
    public string $activeSlug   = '';
    public string $activeTitle  = '';
    public string $filterCat    = 'tous';

    public function mount(): void
    {
        $this->tracks = config('relaxation_tracks');
    }

    public function setFilter(string $cat): void
    {
        $this->filterCat = $cat;
    }

    public function selectTrack(string $slug): void
    {
        $track = collect($this->tracks)->firstWhere('slug', $slug);
        if (! $track) return;

        $this->activeSlug  = $slug;
        $this->activeTitle = $track['title'];

        // Déclencher la lecture dans le mini-player via event JS
        $this->dispatch('play-track', slug: $slug, title: $track['title']);
    }

    public function logListen(string $slug, int $duration): void
    {
        RelaxationSession::create([
            'user_id'          => Auth::id(),
            'track_slug'       => $slug,
            'duration_seconds' => $duration,
            'listened_at'      => now(),
        ]);
    }

    public function getFilteredTracksProperty(): array
    {
        if ($this->filterCat === 'tous') {
            return $this->tracks;
        }
        return collect($this->tracks)
            ->where('category', $this->filterCat)
            ->values()
            ->toArray();
    }

    public function render()
    {
        return view('livewire.detente.musique', [
            'filteredTracks' => $this->filteredTracks,
            'categories'     => collect($this->tracks)->pluck('category')->unique()->prepend('tous')->values()->toArray(),
        ])->layout('layouts.app', ['title' => 'Musique · Détente']);
    }
}
