<?php

// app/Livewire/Carnet/UnlockForm.php

namespace App\Livewire\Carnet;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class UnlockForm extends Component
{
    public string $hint        = '';
    public bool   $showHint    = false;
    public int    $attempts    = 0;
    public bool   $showReset   = false;
    public string $onSuccess   = 'unlockSuccess';

    // Le salt est renvoyé au JS pour la dérivation PBKDF2
    public ?string $saltHex       = null;
    public ?string $keyCheckHash  = null;

    public function mount(string $onSuccess = 'unlockSuccess'): void
    {
        $this->onSuccess = $onSuccess;
        $key = Auth::user()->encryptionKey;

        if ($key) {
            $this->saltHex      = $key->salt_hex;
            $this->keyCheckHash = $key->key_check_hash;
            $this->hint         = $key->hint ?? '';
        }
    }

    /** Appelé par le JS après vérification réussie du HMAC côté client */
    public function confirmUnlock(): void
    {
        session(['journal_unlocked' => true]);
        $this->dispatch($this->onSuccess);
    }

    /** Appelé par le JS après échec */
    public function reportFailure(): void
    {
        $this->attempts++;
        if ($this->attempts >= 3) {
            $this->showHint  = true;
            $this->showReset = $this->attempts >= 5;
        }
    }

    public function render()
    {
        return view('livewire.carnet.unlock-form');
    }
}
