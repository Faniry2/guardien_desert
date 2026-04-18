{{-- resources/views/espace/carnet/setup.blade.php --}}
@extends('layouts.espace')

@section('title', 'Configurer mon carnet')
@section('breadcrumb', 'Configuration')

@section('content')
<div class="flex items-center justify-center min-h-full py-16 px-4">
    <div class="bg-[#0A1018] border border-[#C9973A]/15 rounded-2xl p-10 w-full max-w-md"
         x-data="setupForm()">

        {{-- Étapes --}}
        <div class="flex items-center gap-2 mb-8">
            <div class="flex-1 h-0.5 rounded-full transition-all" :class="step >= 1 ? 'bg-[#C9973A]' : 'bg-white/[0.06]'"></div>
            <div class="flex-1 h-0.5 rounded-full transition-all" :class="step >= 2 ? 'bg-[#C9973A]' : 'bg-white/[0.06]'"></div>
            <div class="flex-1 h-0.5 rounded-full transition-all" :class="step >= 3 ? 'bg-[#C9973A]' : 'bg-white/[0.06]'"></div>
        </div>

        {{-- Étape 1 --}}
        <div x-show="step === 1">
            <div class="w-14 h-14 mx-auto mb-6 rounded-full bg-[#C9973A]/8 border border-[#C9973A]/20 flex items-center justify-center">
                <svg class="w-6 h-6 fill-none stroke-[#C9973A] stroke-[1.5]" viewBox="0 0 24 24">
                    <path stroke-linecap="round" d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                </svg>
            </div>
            <h2 class="font-serif text-[#E8D5A0] text-2xl font-light text-center mb-3">Sécuriser votre carnet</h2>
            <p class="text-[13px] text-[#C8C0B0]/45 text-center leading-relaxed mb-8">
                Votre carnet sera chiffré avec une phrase secrète connue de vous seul·e.<br /><br />
                <strong class="text-[#C9973A]/70 font-normal">Même nous ne pouvons pas lire votre contenu.</strong>
                Si vous l'oubliez, le contenu sera irrécupérable.
            </p>
            <button @click="step = 2"
                    class="w-full py-3 bg-[#C9973A]/10 border border-[#C9973A]/25 rounded-xl
                           text-[13px] text-[#C9973A] tracking-wider hover:bg-[#C9973A]/18 transition-all">
                Je comprends, continuer →
            </button>
        </div>

        {{-- Étape 2 --}}
        <div x-show="step === 2">
            <h2 class="font-serif text-[#E8D5A0] text-2xl font-light text-center mb-2">Votre phrase secrète</h2>
            <p class="text-[12px] text-[#C8C0B0]/35 text-center mb-6">Minimum 12 caractères. Mémorisez-la bien.</p>

            {{-- Phrase secrète --}}
            <label class="block text-[9px] tracking-widest uppercase text-[#C9973A]/40 mb-2">Phrase secrète</label>
            <div class="relative mb-3" x-data="{ show: false }">
                <input :type="show ? 'text' : 'password'" x-model="passphrase"
                       placeholder="Ex: MonChemin2024Lumière"
                       class="w-full bg-white/[0.03] border border-[#C9973A]/15 rounded-lg px-4 py-2.5 pr-11
                              text-[13px] text-[#E0D5C5] placeholder-[#C8C0B0]/20 outline-none
                              focus:border-[#C9973A]/40 transition-colors" />
                <button type="button" @click="show = !show"
                        class="absolute right-3 top-1/2 -translate-y-1/2 text-[#C9973A]/40
                               hover:text-[#C9973A] transition-colors">
                    {{-- Oeil oudjat fermé --}}
                    <svg x-show="!show" class="w-5 h-5" viewBox="0 0 24 24" fill="none">
                        <ellipse cx="12" cy="12" rx="9" ry="5.5" stroke="#C9973A" stroke-width="1.2" opacity="0.5"/>
                        <path d="M12 9.5 L12 8 M12 9.5 L10.5 8.5 M12 9.5 L13.5 8.5" stroke="#C9973A" stroke-width="1" stroke-linecap="round" opacity="0.6"/>
                        <circle cx="12" cy="12" r="2.2" stroke="#C9973A" stroke-width="1.3"/>
                        <circle cx="12" cy="12" r="0.8" fill="#C9973A" opacity="0.7"/>
                        <path d="M8 10 Q12 7 16 10" stroke="#C9973A" stroke-width="1" fill="none" opacity="0.4"/>
                        <path d="M7.5 12 Q12 15.5 16.5 12" stroke="#C9973A" stroke-width="1.2" fill="none" opacity="0.5"/>
                        <path d="M5 12 L4 10.5 M19 12 L20 10.5" stroke="#C9973A" stroke-width="1" stroke-linecap="round" opacity="0.4"/>
                    </svg>
                    {{-- Oeil oudjat ouvert --}}
                    <svg x-show="show" class="w-5 h-5" viewBox="0 0 24 24" fill="none">
                        <ellipse cx="12" cy="12" rx="9" ry="5.5" stroke="#C9973A" stroke-width="1.4" opacity="0.8"/>
                        <path d="M12 8.5 L12 6.5 M12 8.5 L10 7 M12 8.5 L14 7" stroke="#C9973A" stroke-width="1.2" stroke-linecap="round" opacity="0.9"/>
                        <circle cx="12" cy="12" r="2.5" stroke="#C9973A" stroke-width="1.5"/>
                        <circle cx="12" cy="12" r="1" fill="#C9973A"/>
                        <path d="M7 9.5 Q12 6 17 9.5" stroke="#C9973A" stroke-width="1.2" fill="none" opacity="0.6"/>
                        <path d="M6.5 12 Q12 16.5 17.5 12" stroke="#C9973A" stroke-width="1.5" fill="none" opacity="0.8"/>
                        <path d="M4.5 12 L3 10 M19.5 12 L21 10" stroke="#C9973A" stroke-width="1.2" stroke-linecap="round" opacity="0.7"/>
                        <path d="M10.5 11 L11.5 10 L12.5 11" stroke="#C9973A" stroke-width="0.8" fill="none" opacity="0.5"/>
                    </svg>
                </button>
            </div>

            {{-- Confirmer --}}
            <label class="block text-[9px] tracking-widest uppercase text-[#C9973A]/40 mb-2">Confirmer</label>
            <div class="relative mb-3" x-data="{ show: false }">
                <input :type="show ? 'text' : 'password'" x-model="passphraseConfirm"
                       placeholder="Répétez la phrase"
                       class="w-full bg-white/[0.03] border border-[#C9973A]/15 rounded-lg px-4 py-2.5 pr-11
                              text-[13px] text-[#E0D5C5] placeholder-[#C8C0B0]/20 outline-none
                              focus:border-[#C9973A]/40 transition-colors" />
                <button type="button" @click="show = !show"
                        class="absolute right-3 top-1/2 -translate-y-1/2 text-[#C9973A]/40
                               hover:text-[#C9973A] transition-colors">
                    <svg x-show="!show" class="w-5 h-5" viewBox="0 0 24 24" fill="none">
                        <ellipse cx="12" cy="12" rx="9" ry="5.5" stroke="#C9973A" stroke-width="1.2" opacity="0.5"/>
                        <path d="M12 9.5 L12 8 M12 9.5 L10.5 8.5 M12 9.5 L13.5 8.5" stroke="#C9973A" stroke-width="1" stroke-linecap="round" opacity="0.6"/>
                        <circle cx="12" cy="12" r="2.2" stroke="#C9973A" stroke-width="1.3"/>
                        <circle cx="12" cy="12" r="0.8" fill="#C9973A" opacity="0.7"/>
                        <path d="M8 10 Q12 7 16 10" stroke="#C9973A" stroke-width="1" fill="none" opacity="0.4"/>
                        <path d="M7.5 12 Q12 15.5 16.5 12" stroke="#C9973A" stroke-width="1.2" fill="none" opacity="0.5"/>
                        <path d="M5 12 L4 10.5 M19 12 L20 10.5" stroke="#C9973A" stroke-width="1" stroke-linecap="round" opacity="0.4"/>
                    </svg>
                    <svg x-show="show" class="w-5 h-5" viewBox="0 0 24 24" fill="none">
                        <ellipse cx="12" cy="12" rx="9" ry="5.5" stroke="#C9973A" stroke-width="1.4" opacity="0.8"/>
                        <path d="M12 8.5 L12 6.5 M12 8.5 L10 7 M12 8.5 L14 7" stroke="#C9973A" stroke-width="1.2" stroke-linecap="round" opacity="0.9"/>
                        <circle cx="12" cy="12" r="2.5" stroke="#C9973A" stroke-width="1.5"/>
                        <circle cx="12" cy="12" r="1" fill="#C9973A"/>
                        <path d="M7 9.5 Q12 6 17 9.5" stroke="#C9973A" stroke-width="1.2" fill="none" opacity="0.6"/>
                        <path d="M6.5 12 Q12 16.5 17.5 12" stroke="#C9973A" stroke-width="1.5" fill="none" opacity="0.8"/>
                        <path d="M4.5 12 L3 10 M19.5 12 L21 10" stroke="#C9973A" stroke-width="1.2" stroke-linecap="round" opacity="0.7"/>
                        <path d="M10.5 11 L11.5 10 L12.5 11" stroke="#C9973A" stroke-width="0.8" fill="none" opacity="0.5"/>
                    </svg>
                </button>
            </div>

            {{-- Indice --}}
            <label class="block text-[9px] tracking-widest uppercase text-[#C9973A]/40 mb-2">Indice (optionnel)</label>
            <input type="text" x-model="hint" placeholder="Un indice pour vous aider..."
                   class="w-full bg-white/[0.03] border border-[#C9973A]/15 rounded-lg px-4 py-2.5
                          text-[13px] text-[#E0D5C5] placeholder-[#C8C0B0]/20 outline-none
                          focus:border-[#C9973A]/40 transition-colors mb-5" />

            <p x-show="error" x-text="error" class="text-[12px] text-red-400/80 text-center mb-3"></p>

            <button @click="validatePassphrase()"
                    class="w-full py-3 bg-[#1A2A3A] border border-[#C9973A]/20 rounded-xl
                           text-[13px] text-[#C9973A] tracking-wider hover:bg-[#C9973A]/10 transition-all">
                Suivant →
            </button>
        </div>

        {{-- Étape 3 --}}
        <div x-show="step === 3">
            <h2 class="font-serif text-[#E8D5A0] text-2xl font-light text-center mb-3">Tout est prêt</h2>
            <p class="text-[13px] text-[#C8C0B0]/40 text-center mb-6">Votre carnet va être créé et sécurisé.</p>
            <div class="bg-[#C9973A]/5 border border-[#C9973A]/10 rounded-lg p-4 mb-6">
                <p class="text-[11px] text-[#C9973A]/50 mb-1">Votre indice :</p>
                <p class="text-[13px] text-[#E0D5C5]/60 italic" x-text="hint || 'Aucun indice défini'"></p>
            </div>
            <p x-show="error" x-text="error" class="text-[12px] text-red-400/80 text-center mb-3"></p>
            <button @click="createJournal()" :disabled="loading"
                    class="w-full py-3 bg-[#C9973A] rounded-xl text-[13px] text-[#0A1018]
                           font-medium tracking-wider hover:bg-[#E8C47A] disabled:opacity-40 transition-all">
                <span x-show="!loading">✦ Créer mon carnet</span>
                <span x-show="loading">Chiffrement en cours...</span>
            </button>
        </div>
    </div>
</div>

@push('scripts')
<script>
function setupForm() {
    return {
        step: 1, passphrase: '', passphraseConfirm: '', hint: '', error: '', loading: false,
        validatePassphrase() {
            this.error = '';
            if (this.passphrase.length < 12) { this.error = 'Minimum 12 caractères.'; return; }
            if (this.passphrase !== this.passphraseConfirm) { this.error = 'Les phrases ne correspondent pas.'; return; }
            this.step = 3;
        },
        async createJournal() {
            this.loading = true;
            this.error = '';
            try {
                //await fetch('/sanctum/csrf-cookie', { credentials: 'include' });
                //const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
                await window.JournalCrypto.setupEncryption(this.passphrase, this.hint);
                window.location.href = '{{ route("carnet.index") }}';
            } catch(e) {
                this.error = 'Erreur lors de la création. Réessayez.';
                this.loading = false;
            }
        }
    }
}
</script>
@endpush
@endsection