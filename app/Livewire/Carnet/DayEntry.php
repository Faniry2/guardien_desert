<?php

// app/Livewire/Carnet/DayEntry.php

namespace App\Livewire\Carnet;

use App\Models\Carnet;
use App\Models\CarnetEntry;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class DayEntry extends Component
{
    public int    $dayNumber;
    public bool   $isUnlocked  = false;
    public bool   $isSaving    = false;
    public string $saveStatus  = 'Toutes les modifications sont sauvegardées';

    // Champs NON chiffrés (sûrs à passer via Livewire)
    public bool   $isCompleted = false;

    // Les champs chiffrés sont gérés côté JS uniquement.
    // Livewire ne reçoit que les blobs chiffrés base64.
    public ?string $titleEncrypted      = null;
    public ?string $colorEncrypted      = null;
    public ?string $moodEncrypted       = null;
    public ?string $highlightEncrypted  = null;
    public ?string $reflectionEncrypted = null;
    public ?string $questionsEncrypted  = null;
    public ?string $freeWritingEncrypted= null;
    public ?string $awarenessEncrypted  = null;
    public ?string $commitmentEncrypted = null;

    protected Carnet $carnet;

    public function mount(int $day): void
    {
        $this->dayNumber = $day;
        $this->carnet    = Auth::user()->carnet;

        $entry = $this->getOrCreateEntry();
        $this->isCompleted = $entry->is_completed;

        // Charger les blobs chiffrés (seront déchiffrés côté JS)
        $this->titleEncrypted       = $entry->title_encrypted;
        $this->colorEncrypted       = $entry->color_encrypted;
        $this->moodEncrypted        = $entry->mood_encrypted;
        $this->highlightEncrypted   = $entry->highlight_encrypted;
        $this->reflectionEncrypted  = $entry->reflection_encrypted;
        $this->questionsEncrypted   = $entry->questions_encrypted;
        $this->freeWritingEncrypted = $entry->free_writing_encrypted;
        $this->awarenessEncrypted   = $entry->awareness_encrypted;
        $this->commitmentEncrypted  = $entry->commitment_encrypted;

        // Vérifier si la session est déverrouillée
        // (le JS postera un signal Livewire après déverrouillage)
        $this->isUnlocked = session()->has('journal_unlocked');
    }

    /**
     * Reçoit les données chiffrées depuis le JS et les sauvegarde.
     * Appelé via: $wire.call('saveEncrypted', { ... })
     */
    public function saveEncrypted(array $encryptedFields): void
    {
        $this->isSaving   = true;
        $this->saveStatus = 'Chiffrement en cours...';

        $entry = $this->getOrCreateEntry();

        $entry->update([
            'title_encrypted'       => $encryptedFields['title']       ?? $entry->title_encrypted,
            'color_encrypted'       => $encryptedFields['color']       ?? $entry->color_encrypted,
            'mood_encrypted'        => $encryptedFields['mood']        ?? $entry->mood_encrypted,
            'highlight_encrypted'   => $encryptedFields['highlight']   ?? $entry->highlight_encrypted,
            'reflection_encrypted'  => $encryptedFields['reflection']  ?? $entry->reflection_encrypted,
            'questions_encrypted'   => $encryptedFields['questions']   ?? $entry->questions_encrypted,
            'free_writing_encrypted'=> $encryptedFields['freeWriting'] ?? $entry->free_writing_encrypted,
            'awareness_encrypted'   => $encryptedFields['awareness']   ?? $entry->awareness_encrypted,
            'commitment_encrypted'  => $encryptedFields['commitment']  ?? $entry->commitment_encrypted,
        ]);

        $this->isSaving   = false;
        $this->saveStatus = 'Sauvegardé et chiffré ✓';
    }

    public function markComplete(): void
    {
        $entry = $this->getOrCreateEntry();
        $entry->update(['is_completed' => true]);
        $this->isCompleted = true;
    }

    public function unlockSuccess(): void
    {
        $this->isUnlocked = true;
        session(['journal_unlocked' => true]);
    }

    private function getOrCreateEntry(): CarnetEntry
    {
        return CarnetEntry::firstOrCreate(
            [
                'carnet_id'  => $this->carnet->id,
                'day_number' => $this->dayNumber,
            ],
            [
                'entry_date' => $this->carnet->start_date->addDays($this->dayNumber - 1),
            ]
        );
    }

    public function render()
    {
        return view('livewire.carnet.day-entry', [
            'prevDay' => $this->dayNumber > 1  ? $this->dayNumber - 1 : null,
            'nextDay' => $this->dayNumber < 90 ? $this->dayNumber + 1 : null,
        ])->layout('layouts.app', ['title' => "Jour {$this->dayNumber}"]);
    }
}
