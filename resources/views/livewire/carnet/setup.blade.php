<div class="flex items-center justify-center min-h-screen py-16">
    <div class="bg-[#0A1018] border border-[#C9973A]/15 rounded-2xl p-10 w-[420px]"
         x-data="setupForm()">

        {{-- Étapes --}}
        <div class="flex items-center gap-2 mb-8">
            <div class="flex-1 h-0.5 rounded-full transition-all"
                 :class="step >= 1 ? 'bg-[#C9973A]' : 'bg-white/[0.06]'"></div>
            <div class="flex-1 h-0.5 rounded-full transition-all"
                 :class="step >= 2 ? 'bg-[#C9973A]' : 'bg-white/[0.06]'"></div>
            <div class="flex-1 h-0.5 rounded-full transition-all"
                 :class="step >= 3 ? 'bg-[#C9973A]' : 'bg-white/[0.06]'"></div>
        </div>

        {{-- Étape 1 : Intro --}}
        <div x-show="step === 1">
            <div class="w-14 h-14 mx-auto mb-6 rounded-full bg-[#C9973A]/8
                        border border-[#C9973A]/20 flex items-center justify-center">
                <svg class="w-6 h-6 fill-none stroke-[#C9973A] stroke-[1.5]" viewBox="0 0 24 24">
                    <path stroke-linecap="round" d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                </svg>
            </div>
            <h2 class="font-serif text-[#E8D5A0] text-2xl font-light text-center mb-3">
                Sécuriser votre carnet
            </h2>
            <p class="text-[13px] text-[#C8C0B0]/45 text-center leading-relaxed mb-8">
                Votre carnet sera chiffré avec une phrase secrète
                connue de vous seul·e.<br /><br />
                <strong class="text-[#C9973A]/70 font-normal">Même nous ne pouvons pas lire votre contenu.</strong>
                Si vous oubliez votre phrase, le contenu sera irrécupérable.
            </p>
            <button @click="step = 2"
                    class="w-full py-3 bg-[#C9973A]/10 border border-[#C9973A]/25
                           rounded-xl text-[13px] text-[#C9973A] tracking-wider
                           hover:bg-[#C9973A]/18 transition-all">
                Je comprends, continuer →
            </button>
        </div>

        {{-- Étape 2 : Choisir la passphrase --}}
        <div x-show="step === 2">
            <h2 class="font-serif text-[#E8D5A0] text-2xl font-light text-center mb-2">
                Votre phrase secrète
            </h2>
            <p class="text-[12px] text-[#C8C0B0]/35 text-center mb-6 leading-relaxed">
                Choisissez une phrase mémorable. Minimum 12 caractères.
            </p>

            <label class="block text-[9px] tracking-widest uppercase text-[#C9973A]/40 mb-2">
                Phrase secrète
            </label>
            <input type="password" x-model="passphrase"
                   placeholder="Ex: MonChemin2024Lumière"
                   class="w-full bg-white/[0.03] border border-[#C9973A]/15 rounded-lg
                          px-4 py-2.5 text-[13px] text-[#E0D5C5] placeholder-[#C8C0B0]/20
                          outline-none focus:border-[#C9973A]/40 transition-colors mb-3" />

            <label class="block text-[9px] tracking-widest uppercase text-[#C9973A]/40 mb-2">
                Confirmer
            </label>
            <input type="password" x-model="passphraseConfirm"
                   placeholder="Répétez la phrase"
                   class="w-full bg-white/[0.03] border border-[#C9973A]/15 rounded-lg
                          px-4 py-2.5 text-[13px] text-[#E0D5C5] placeholder-[#C8C0B0]/20
                          outline-none focus:border-[#C9973A]/40 transition-colors mb-3" />

            <label class="block text-[9px] tracking-widest uppercase text-[#C9973A]/40 mb-2">
                Indice (optionnel, visible en cas d'oubli)
            </label>
            <input type="text" x-model="hint"
                   placeholder="Un indice pour vous aider..."
                   class="w-full bg-white/[0.03] border border-[#C9973A]/15 rounded-lg
                          px-4 py-2.5 text-[13px] text-[#E0D5C5] placeholder-[#C8C0B0]/20
                          outline-none focus:border-[#C9973A]/40 transition-colors mb-5" />

            <p x-show="error" x-text="error"
               class="text-[12px] text-red-400/80 text-center mb-3"></p>

            <button @click="validatePassphrase()"
                    class="w-full py-3 bg-[#1A2A3A] border border-[#C9973A]/20
                           rounded-xl text-[13px] text-[#C9973A] tracking-wider
                           hover:bg-[#C9973A]/10 transition-all">
                Suivant →
            </button>
        </div>

        {{-- Étape 3 : Confirmation --}}
        <div x-show="step === 3">
            <h2 class="font-serif text-[#E8D5A0] text-2xl font-light text-center mb-3">
                Tout est prêt
            </h2>
            <p class="text-[13px] text-[#C8C0B0]/40 text-center mb-6 leading-relaxed">
                Votre carnet va être créé et sécurisé.<br />
                Cette opération est irréversible.
            </p>

            <div class="bg-[#C9973A]/5 border border-[#C9973A]/10 rounded-lg p-4 mb-6">
                <p class="text-[11px] text-[#C9973A]/50 mb-1">Votre indice :</p>
                <p class="text-[13px] text-[#E0D5C5]/60 italic" x-text="hint || 'Aucun indice défini'"></p>
            </div>

            <button @click="createJournal()" :disabled="loading"
                    class="w-full py-3 bg-[#C9973A] rounded-xl text-[13px]
                           text-[#0A1018] font-medium tracking-wider
                           hover:bg-[#E8C47A] disabled:opacity-40 transition-all">
                <span x-show="!loading">✦ Créer mon carnet</span>
                <span x-show="loading">Chiffrement en cours...</span>
            </button>
        </div>
    </div>

    <script>
    function setupForm() {
        return {
            step: 1,
            passphrase: '',
            passphraseConfirm: '',
            hint: '',
            error: '',
            loading: false,

            validatePassphrase() {
                this.error = '';
                if (this.passphrase.length < 12) {
                    this.error = 'La phrase doit faire au moins 12 caractères.'; return;
                }
                if (this.passphrase !== this.passphraseConfirm) {
                    this.error = 'Les deux phrases ne correspondent pas.'; return;
                }
                this.step = 3;
            },

            async createJournal() {
                this.loading = true;
                try {
                    await fetch('/sanctum/csrf-cookie', { credentials: 'include' });
                    await window.JournalCrypto.setupEncryption(this.passphrase, this.hint);
                    window.location.href = '/espace/carnet';
                } catch(e) {
                    this.error = 'Erreur lors de la création. Réessayez.';
                    this.loading = false;
                }
            }
        }
    }
    </script>
</div>