{{-- resources/views/espace/carnet/day.blade.php --}}
@extends('layouts.espace')

@section('title', 'Jour ' . $dayNumber)
@section('breadcrumb', 'Jour ' . $dayNumber)

@section('content')
<div class="px-6 py-8 max-w-2xl"
     x-data="carnetDay({
         dayNumber: {{ $dayNumber }},
         encryptedFields: {{ json_encode($encryptedFields) }}
     })">

    {{-- Unlock overlay --}}
    <div x-show="!unlocked"
         class="fixed inset-0 bg-[#0A1018]/95 flex items-center justify-center z-50"
         style="display:none">
        <div class="bg-[#0E1621] border border-[#C9973A]/15 rounded-2xl p-10 w-full max-w-sm text-center">
            <div class="w-14 h-14 mx-auto mb-6 rounded-full bg-[#C9973A]/8 border border-[#C9973A]/20 flex items-center justify-center">
                <svg class="w-6 h-6 fill-none stroke-[#C9973A] stroke-[1.5]" viewBox="0 0 24 24">
                    <rect x="3" y="11" width="18" height="11" rx="2"/>
                    <path stroke-linecap="round" d="M7 11V7a5 5 0 0110 0v4"/>
                </svg>
            </div>
            <h2 class="font-serif text-[#E8D5A0] text-2xl font-light mb-2">Carnet de Traversée</h2>
            <p class="text-[13px] text-[#C8C0B0]/40 mb-6 leading-relaxed">
                Entrez votre phrase secrète pour déverrouiller.
            </p>
            <input type="password" x-model="passphrase" @keydown.enter="unlock()"
                   placeholder="••••••••••••"
                   class="w-full bg-white/[0.03] border border-[#C9973A]/15 rounded-xl px-4 py-3
                          text-[15px] text-[#E0D5C5] text-center tracking-[0.12em]
                          placeholder-[#C8C0B0]/15 outline-none focus:border-[#C9973A]/40 mb-3" />
            <p x-show="unlockError" x-text="unlockError" class="text-[12px] text-red-400/80 mb-3"></p>
            @if($hint)
                <p class="text-[11px] text-[#C9973A]/40 italic mb-4">Indice : {{ $hint }}</p>
            @endif
            <button @click="unlock()" :disabled="unlocking"
                    class="w-full py-3 bg-[#1A2A3A] border border-[#C9973A]/20 rounded-xl
                           text-[13px] text-[#C9973A] tracking-wider hover:bg-[#C9973A]/10
                           disabled:opacity-40 transition-all">
                <span x-show="!unlocking">Déverrouiller</span>
                <span x-show="unlocking">Dérivation de clé...</span>
            </button>
        </div>
    </div>

    {{-- Contenu du jour --}}
    <div x-show="unlocked" style="display:none">

        {{-- Navigation --}}
        <div class="flex items-center gap-4 mb-8">
            @if($prevDay)
                <a href="{{ route('carnet.day', $prevDay) }}" class="text-[#C9973A]/40 hover:text-[#C9973A] text-sm transition-colors">‹ Jour {{ $prevDay }}</a>
            @else
                <span></span>
            @endif
            <h1 class="font-serif text-[#E8D5A0] text-3xl font-light flex-1 text-center">Jour {{ $dayNumber }}</h1>
            @if($nextDay)
                <a href="{{ route('carnet.day', $nextDay) }}" class="text-[#C9973A]/40 hover:text-[#C9973A] text-sm transition-colors">Jour {{ $nextDay }} ›</a>
            @else
                <span></span>
            @endif
        </div>

        {{-- Titre + Couleur --}}
        <div class="grid grid-cols-2 gap-4 mb-5">
            <div>
                <label class="block text-[9px] tracking-widest uppercase text-[#C9973A]/40 mb-2">Titre du jour</label>
                <input type="text" x-model="fields.title" @input="debounceSave()"
                       placeholder="Ce jour s'appelle..."
                       class="w-full bg-white/[0.03] border border-[#C9973A]/15 rounded-lg px-4 py-2.5
                              text-[13px] text-[#E0D5C5] placeholder-[#C8C0B0]/20 outline-none
                              focus:border-[#C9973A]/40 transition-colors" />
            </div>
            <div>
                <label class="block text-[9px] tracking-widest uppercase text-[#C9973A]/40 mb-2">Couleur du jour</label>
                <div class="flex items-center gap-2 mt-2">
                    <template x-for="c in colors" :key="c">
                        <button @click="fields.color = c; debounceSave()"
                                class="w-6 h-6 rounded-full border-2 transition-transform hover:scale-110"
                                :class="fields.color === c ? 'border-white/70 scale-110' : 'border-transparent'"
                                :style="`background:${c}`"></button>
                    </template>
                </div>
            </div>
        </div>

        {{-- Humeur --}}
        <div class="mb-5">
            <label class="block text-[9px] tracking-widest uppercase text-[#C9973A]/40 mb-2">Humeur du jour</label>
            <div class="flex flex-wrap gap-2">
                <template x-for="mood in moods" :key="mood">
                    <button @click="fields.mood = mood; debounceSave()"
                            class="px-3.5 py-1.5 text-[12px] rounded-full border transition-all"
                            :class="fields.mood === mood
                                ? 'bg-[#C9973A] text-[#0A1018] border-[#C9973A]'
                                : 'bg-white/[0.03] border-[#C9973A]/15 text-[#C8C0B0]/60 hover:border-[#C9973A]/35'">
                        <span x-text="mood"></span>
                    </button>
                </template>
            </div>
        </div>

        {{-- Moment + Prise de conscience --}}
        <div class="grid grid-cols-2 gap-4 mb-5">
            <div>
                <label class="block text-[9px] tracking-widest uppercase text-[#C9973A]/40 mb-2">Moment marquant</label>
                <textarea x-model="fields.highlight" @input="debounceSave()" rows="3"
                          placeholder="Ce qui s'est passé..."
                          class="w-full bg-white/[0.03] border border-[#C9973A]/15 rounded-lg px-4 py-2.5
                                 text-[13px] text-[#E0D5C5] placeholder-[#C8C0B0]/20 outline-none
                                 focus:border-[#C9973A]/40 resize-none transition-colors"></textarea>
            </div>
            <div>
                <label class="block text-[9px] tracking-widest uppercase text-[#C9973A]/40 mb-2">Prise de conscience</label>
                <textarea x-model="fields.awareness" @input="debounceSave()" rows="3"
                          placeholder="Ce qui s'est révélé..."
                          class="w-full bg-white/[0.03] border border-[#C9973A]/15 rounded-lg px-4 py-2.5
                                 text-[13px] text-[#E0D5C5] placeholder-[#C8C0B0]/20 outline-none
                                 focus:border-[#C9973A]/40 resize-none transition-colors"></textarea>
            </div>
        </div>

        <div class="h-px bg-[#C9973A]/8 my-5"></div>

        {{-- Questions --}}
        <div class="mb-5">
            <label class="block text-[9px] tracking-widest uppercase text-[#C9973A]/40 mb-3">Questions d'exploration</label>
            <div class="border-l-2 border-[#C9973A] pl-4 space-y-3">
                <template x-for="q in questions" :key="q">
                    <p @click="appendQuestion(q)"
                       class="font-serif text-[15px] text-[#E8D5A0]/60 italic cursor-pointer hover:text-[#C9973A] transition-colors">
                        ◦ <span x-text="q"></span>
                    </p>
                </template>
            </div>
        </div>

        {{-- Écriture libre --}}
        <div class="mb-5">
            <label class="block text-[9px] tracking-widest uppercase text-[#C9973A]/40 mb-2">Écriture libre</label>
            <div contenteditable="true" x-ref="freeWriting"
                 @input="fields.freeWriting = $el.innerText; debounceSave()"
                 data-placeholder="Laissez couler vos mots..."
                 class="min-h-[160px] bg-white/[0.02] border border-[#C9973A]/10 rounded-xl p-5
                        font-serif text-[18px] text-[#E0D5C5]/80 leading-[1.9] outline-none
                        focus:border-[#C9973A]/30 transition-colors
                        empty:before:content-[attr(data-placeholder)]
                        empty:before:text-[#C8C0B0]/20 empty:before:italic empty:before:pointer-events-none">
            </div>
        </div>

        {{-- Réflexion --}}
        <div class="mb-5">
            <label class="block text-[9px] tracking-widest uppercase text-[#C9973A]/40 mb-2">Réflexion du jour</label>
            <textarea x-model="fields.reflection" @input="debounceSave()" rows="4"
                      placeholder="Ce que ce jour m'a appris..."
                      class="w-full bg-white/[0.03] border border-[#C9973A]/15 rounded-lg px-4 py-3
                             text-[13px] text-[#E0D5C5] placeholder-[#C8C0B0]/20 outline-none
                             focus:border-[#C9973A]/40 resize-none transition-colors"></textarea>
        </div>

        {{-- Engagement --}}
        <div class="mb-8">
            <label class="block text-[9px] tracking-widest uppercase text-[#C9973A]/40 mb-2">Engagement pour demain</label>
            <textarea x-model="fields.commitment" @input="debounceSave()" rows="2"
                      placeholder="Je m'engage à..."
                      class="w-full bg-white/[0.03] border border-[#C9973A]/15 rounded-lg px-4 py-2.5
                             text-[13px] text-[#E0D5C5] placeholder-[#C8C0B0]/20 outline-none
                             focus:border-[#C9973A]/40 resize-none transition-colors"></textarea>
        </div>

        {{-- Save --}}
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-2 text-[12px] text-[#C8C0B0]/35">
                <span class="w-1.5 h-1.5 rounded-full bg-[#C9973A]"
                      :class="saving ? 'animate-pulse opacity-100' : 'opacity-0'"></span>
                <span x-text="saveStatus"></span>
            </div>
            <div class="flex gap-2">
                <button @click="manualSave()"
                        class="px-6 py-2 bg-[#0A1018] border border-[#C9973A]/20 rounded-lg
                               text-[12px] text-[#C9973A] hover:bg-[#C9973A]/8 transition-all">
                    Sauvegarder ✦
                </button>
                @if(!$isCompleted)
                    <form method="POST" action="{{ route('carnet.complete', $dayNumber) }}" class="inline">
                        @csrf
                        <button type="submit"
                                class="px-6 py-2 bg-[#C9973A]/10 border border-[#C9973A]/30 rounded-lg
                                       text-[12px] text-[#C9973A] hover:bg-[#C9973A]/20 transition-all">
                            Marquer complet ✓
                        </button>
                    </form>
                @else
                    <span class="px-4 py-2 text-[12px] text-[#C9973A]/50 italic">Jour complété ✓</span>
                @endif
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function carnetDay({ dayNumber, encryptedFields }) {
    return {
        dayNumber,
        unlocked: false,
        unlocking: false,
        passphrase: '',
        unlockError: '',
        saving: false,
        saveStatus: 'Toutes les modifications sont sauvegardées',
        saveTimer: null,
        fields: { title:'', color:'#C9973A', mood:'', highlight:'', reflection:'', freeWriting:'', awareness:'', commitment:'' },
        colors: ['#C9973A','#4A7FA5','#7B9E6B','#A0736E','#8E6BAD','#4A4A5A','#D4A0A0'],
        moods: ['En paix','Joyeux·se','Mélancolique','Incertain·e','Puissant·e','Vulnérable','Curieux·se'],
        questions: [
            "Qu'est-ce que je refuse encore de voir en moi ?",
            "Quelle est la chose la plus difficile que j'ai traversée aujourd'hui ?",
            "Si ce jour était un enseignement, quel serait-il ?",
            "Qu'est-ce qui m'a touché·e profondément ?",
        ],

        async unlock() {
            if (!this.passphrase.trim()) return;
            this.unlocking = true; this.unlockError = '';
            try {
                const saltData = await fetch('/api/encryption-salt', { credentials:'include' }).then(r => r.json());
                if (!saltData.configured) { this.unlockError = 'Carnet non configuré.'; return; }
                const key   = await window.JournalCrypto.deriveKey(this.passphrase, saltData.salt_hex);
                const valid = await window.JournalCrypto.verifyKey(key, saltData.key_check_hash);
                if (!valid) { this.unlockError = 'Phrase secrète incorrecte.'; return; }
                await window.JournalCrypto.storeSessionKey(key);
                await this.decryptFields(key);
                this.unlocked = true;
            } catch(e) {
                this.unlockError = 'Erreur de déchiffrement.';
            } finally {
                this.unlocking = false; this.passphrase = '';
            }
        },

        async init() {
            const key = await window.JournalCrypto.getSessionKey();
            if (key) { await this.decryptFields(key); this.unlocked = true; }
        },

        async decryptFields(key) {
            const map = { title:'title', color:'color', mood:'mood', highlight:'highlight',
                          reflection:'reflection', freeWriting:'free_writing',
                          awareness:'awareness', commitment:'commitment' };
            for (const [field, apiField] of Object.entries(map)) {
                const blob = encryptedFields[apiField + '_encrypted'];
                if (blob) { try { this.fields[field] = await window.JournalCrypto.decrypt(blob, key); } catch(e) {} }
            }
            if (this.$refs.freeWriting) this.$refs.freeWriting.innerText = this.fields.freeWriting;
        },

        appendQuestion(q) {
            const fw = this.$refs.freeWriting;
            fw.innerText = (fw.innerText.trim() ? fw.innerText.trim() + '\n\n' : '') + q + '\n';
            this.fields.freeWriting = fw.innerText;
            fw.focus(); this.debounceSave();
        },

        debounceSave() {
            clearTimeout(this.saveTimer);
            this.saving = true; this.saveStatus = 'Chiffrement en cours...';
            this.saveTimer = setTimeout(() => this.save(), 1400);
        },

        async manualSave() { clearTimeout(this.saveTimer); await this.save(); },

        async save() {
            const key = await window.JournalCrypto.getSessionKey();
            if (!key) { this.saveStatus = 'Carnet verrouillé'; return; }
            const enc = {};
            const map = { title:'title', color:'color', mood:'mood', highlight:'highlight',
                          reflection:'reflection', freeWriting:'free_writing',
                          awareness:'awareness', commitment:'commitment' };
            for (const [field, apiField] of Object.entries(map)) {
                if (this.fields[field]) enc[apiField + '_encrypted'] = await window.JournalCrypto.encrypt(this.fields[field], key);
            }
            await fetch(`/api/carnet/entries/{{ $dayNumber }}`, {
                method: 'PUT', credentials: 'include',
                headers: { 'Content-Type':'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content },
                body: JSON.stringify(enc)
            });
            this.saving = false; this.saveStatus = 'Sauvegardé et chiffré ✓';
        }
    }
}
</script>
@endpush
@endsection
