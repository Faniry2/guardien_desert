{{-- resources/views/livewire/carnet/unlock-form.blade.php --}}
<div class="bg-[#0A1018] border border-[#C9973A]/15 rounded-2xl p-10 w-[360px]"
     x-data="unlockForm({
         saltHex:      {{ json_encode($saltHex) }},
         keyCheckHash: {{ json_encode($keyCheckHash) }},
         wire:         $wire,
     })">

    {{-- Icône --}}
    <div class="w-14 h-14 mx-auto mb-6 rounded-full bg-[#C9973A]/8 border border-[#C9973A]/20
                flex items-center justify-center">
        <svg class="w-6 h-6 fill-none stroke-[#C9973A] stroke-[1.5] stroke-linecap-round"
             viewBox="0 0 24 24">
            <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
            <path d="M7 11V7a5 5 0 0110 0v4"/>
        </svg>
    </div>

    <h2 class="font-serif text-[#E8D5A0] text-2xl font-light text-center mb-2 tracking-wide">
        Carnet de Traversée
    </h2>
    <p class="text-[13px] text-[#C8C0B0]/40 text-center mb-8 leading-relaxed">
        Votre carnet est chiffré.<br />
        Entrez votre phrase secrète pour déverrouiller.
    </p>

    {{-- Input --}}
    <input type="password"
           x-model="passphrase"
           @keydown.enter="unlock()"
           placeholder="••••••••••••••••"
           class="w-full bg-white/[0.03] border border-[#C9973A]/15 rounded-xl
                  px-4 py-3 text-[15px] text-[#E0D5C5] text-center tracking-[0.12em]
                  placeholder-[#C8C0B0]/15 outline-none focus:border-[#C9973A]/40
                  transition-colors mb-4" />

    {{-- Error --}}
    <p x-show="error" x-text="error"
       class="text-[12px] text-red-400/80 text-center mb-3 -mt-1"></p>

    {{-- Hint --}}
    @if ($showHint && $hint)
        <div class="bg-[#C9973A]/5 border border-[#C9973A]/10 rounded-lg px-4 py-2.5 mb-4">
            <p class="text-[11px] text-[#C9973A]/60 text-center">
                Indice : <em>{{ $hint }}</em>
            </p>
        </div>
    @endif

    {{-- Bouton --}}
    <button @click="unlock()" :disabled="loading"
            class="w-full py-3 bg-[#1A2A3A] border border-[#C9973A]/20 rounded-xl
                   text-[13px] text-[#C9973A] tracking-widest uppercase
                   hover:bg-[#C9973A]/10 hover:border-[#C9973A]/40
                   disabled:opacity-40 transition-all mb-4">
        <span x-show="!loading">Déverrouiller</span>
        <span x-show="loading">Dérivation de clé...</span>
    </button>

    {{-- Reset --}}
    @if ($showReset)
        <p class="text-[11px] text-[#C8C0B0]/25 text-center leading-relaxed">
            Phrase oubliée ?
            <a href="{{ route('carnet.reset') }}"
               class="text-red-400/50 hover:text-red-400 underline cursor-pointer transition-colors"
               onclick="return confirm('Attention : toutes vos entrées seront définitivement supprimées.')">
                Réinitialiser le carnet
            </a>
        </p>
    @else
        <p class="text-[11px] text-[#C8C0B0]/20 text-center leading-relaxed">
            Votre phrase secrète n'est connue que de vous.<br />
            Même nous ne pouvons pas y accéder.
        </p>
    @endif
</div>

@push('scripts')
<script>
function unlockForm({ saltHex, keyCheckHash, wire }) {
    return {
        passphrase: '',
        loading:    false,
        error:      '',

        async unlock() {
            if (!this.passphrase.trim()) return;
            this.loading = true;
            this.error   = '';

            try {
                const key   = await window.JournalCrypto.deriveKey(this.passphrase, saltHex);
                const valid = await window.JournalCrypto.verifyKey(key, keyCheckHash);

                if (valid) {
                    await window.JournalCrypto.storeSessionKey(key);
                    wire.call('confirmUnlock');
                } else {
                    this.error = 'Phrase secrète incorrecte. Réessayez.';
                    wire.call('reportFailure');
                }
            } catch (e) {
                this.error = 'Erreur de déchiffrement. Réessayez.';
            } finally {
                this.loading   = false;
                this.passphrase = '';
            }
        }
    }
}
</script>
@endpush
