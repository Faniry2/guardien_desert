{{-- resources/views/livewire/carnet/day-entry.blade.php --}}
<x-app.pc-layout>
    <x-slot:breadcrumb>Mon carnet · Jour {{ $dayNumber }}</x-slot>

    {{-- Lock overlay --}}
    @if (!$isUnlocked)
        <div class="flex items-center justify-center min-h-full py-16">
            @livewire('carnet.unlock-form', ['onSuccess' => 'unlockSuccess'])
        </div>
    @else

    <div class="px-6 py-8 max-w-2xl"
         x-data="carnetEntry({
             dayNumber: {{ $dayNumber }},
             encryptedFields: {
                 title:       {{ json_encode($titleEncrypted) }},
                 color:       {{ json_encode($colorEncrypted) }},
                 mood:        {{ json_encode($moodEncrypted) }},
                 highlight:   {{ json_encode($highlightEncrypted) }},
                 reflection:  {{ json_encode($reflectionEncrypted) }},
                 questions:   {{ json_encode($questionsEncrypted) }},
                 freeWriting: {{ json_encode($freeWritingEncrypted) }},
                 awareness:   {{ json_encode($awarenessEncrypted) }},
                 commitment:  {{ json_encode($commitmentEncrypted) }},
             }
         })">

        {{-- Navigation jours --}}
        <div class="flex items-center gap-4 mb-8">
            @if ($prevDay)
                <a href="{{ route('carnet.day', $prevDay) }}"
                   class="text-[#C9973A]/40 hover:text-[#C9973A] text-sm transition-colors">‹ Jour {{ $prevDay }}</a>
            @endif
            <h1 class="font-serif text-[#E8D5A0] text-3xl font-light flex-1 text-center">
                Jour {{ $dayNumber }}
            </h1>
            @if ($nextDay)
                <a href="{{ route('carnet.day', $nextDay) }}"
                   class="text-[#C9973A]/40 hover:text-[#C9973A] text-sm transition-colors">Jour {{ $nextDay }} ›</a>
            @endif
        </div>

        {{-- Titre + Couleur --}}
        <div class="grid grid-cols-2 gap-4 mb-6">
            <div>
                <label class="block text-[9px] tracking-widest uppercase text-[#C9973A]/40 mb-2">
                    Titre du jour
                </label>
                <input type="text"
                       x-model="fields.title"
                       @input="debounceSave()"
                       placeholder="Ce jour s'appelle..."
                       class="w-full bg-white/[0.03] border border-[#C9973A]/15 rounded-lg
                              px-4 py-2.5 text-[13px] text-[#E0D5C5] placeholder-[#C8C0B0]/20
                              outline-none focus:border-[#C9973A]/40 transition-colors" />
            </div>
            <div>
                <label class="block text-[9px] tracking-widest uppercase text-[#C9973A]/40 mb-2">
                    Couleur du jour
                </label>
                <div class="flex items-center gap-2 mt-1">
                    <template x-for="c in colors" :key="c.hex">
                        <button @click="fields.color = c.hex; debounceSave()"
                                :title="c.name"
                                class="w-6 h-6 rounded-full border-2 transition-transform hover:scale-110"
                                :class="fields.color === c.hex ? 'border-white/70 scale-110' : 'border-transparent'"
                                :style="`background: ${c.hex}`">
                        </button>
                    </template>
                </div>
            </div>
        </div>

        {{-- Humeur --}}
        <div class="mb-6">
            <label class="block text-[9px] tracking-widest uppercase text-[#C9973A]/40 mb-2">
                Humeur du jour
            </label>
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
        <div class="grid grid-cols-2 gap-4 mb-6">
            <div>
                <label class="block text-[9px] tracking-widest uppercase text-[#C9973A]/40 mb-2">
                    Moment marquant
                </label>
                <textarea x-model="fields.highlight" @input="debounceSave()" rows="3"
                          placeholder="Ce qui s'est passé..."
                          class="w-full bg-white/[0.03] border border-[#C9973A]/15 rounded-lg
                                 px-4 py-2.5 text-[13px] text-[#E0D5C5] placeholder-[#C8C0B0]/20
                                 outline-none focus:border-[#C9973A]/40 resize-none transition-colors">
                </textarea>
            </div>
            <div>
                <label class="block text-[9px] tracking-widest uppercase text-[#C9973A]/40 mb-2">
                    Prise de conscience
                </label>
                <textarea x-model="fields.awareness" @input="debounceSave()" rows="3"
                          placeholder="Ce qui s'est révélé..."
                          class="w-full bg-white/[0.03] border border-[#C9973A]/15 rounded-lg
                                 px-4 py-2.5 text-[13px] text-[#E0D5C5] placeholder-[#C8C0B0]/20
                                 outline-none focus:border-[#C9973A]/40 resize-none transition-colors">
                </textarea>
            </div>
        </div>

        {{-- Divider --}}
        <div class="h-px bg-[#C9973A]/8 my-6"></div>

        {{-- Questions d'exploration --}}
        <div class="mb-6">
            <label class="block text-[9px] tracking-widest uppercase text-[#C9973A]/40 mb-3">
                Questions d'exploration
            </label>
            <div class="border-l-2 border-[#C9973A] pl-4 space-y-3">
                <template x-for="q in questions" :key="q">
                    <p @click="appendQuestion(q)"
                       class="font-serif text-[15px] text-[#E8D5A0]/60 italic cursor-pointer
                              hover:text-[#C9973A] transition-colors leading-relaxed">
                        ◦ <span x-text="q"></span>
                    </p>
                </template>
            </div>
        </div>

        {{-- Écriture libre --}}
        <div class="mb-6">
            <label class="block text-[9px] tracking-widest uppercase text-[#C9973A]/40 mb-2">
                Écriture libre
            </label>
            <div contenteditable="true"
                 x-ref="freeWriting"
                 @input="fields.freeWriting = $el.innerText; debounceSave()"
                 class="min-h-[160px] bg-white/[0.02] border border-[#C9973A]/10 rounded-xl
                        p-5 font-serif text-[18px] text-[#E0D5C5]/80 leading-[1.9]
                        outline-none focus:border-[#C9973A]/30 transition-colors
                        empty:before:content-[attr(data-placeholder)]
                        empty:before:text-[#C8C0B0]/20 empty:before:italic
                        empty:before:pointer-events-none"
                 data-placeholder="Laissez couler vos mots...">
            </div>
        </div>

        {{-- Réflexion --}}
        <div class="mb-6">
            <label class="block text-[9px] tracking-widest uppercase text-[#C9973A]/40 mb-2">
                Réflexion du jour
            </label>
            <textarea x-model="fields.reflection" @input="debounceSave()" rows="4"
                      placeholder="Ce que ce jour m'a appris..."
                      class="w-full bg-white/[0.03] border border-[#C9973A]/15 rounded-lg
                             px-4 py-3 text-[13px] text-[#E0D5C5] placeholder-[#C8C0B0]/20
                             outline-none focus:border-[#C9973A]/40 resize-none transition-colors">
            </textarea>
        </div>

        {{-- Engagement --}}
        <div class="mb-8">
            <label class="block text-[9px] tracking-widest uppercase text-[#C9973A]/40 mb-2">
                Engagement pour demain
            </label>
            <textarea x-model="fields.commitment" @input="debounceSave()" rows="2"
                      placeholder="Je m'engage à..."
                      class="w-full bg-white/[0.03] border border-[#C9973A]/15 rounded-lg
                             px-4 py-2.5 text-[13px] text-[#E0D5C5] placeholder-[#C8C0B0]/20
                             outline-none focus:border-[#C9973A]/40 resize-none transition-colors">
            </textarea>
        </div>

        {{-- Save row --}}
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-2 text-[12px] text-[#C8C0B0]/35">
                <span class="w-1.5 h-1.5 rounded-full bg-[#C9973A]"
                      :class="isSaving ? 'animate-pulse opacity-100' : 'opacity-0'"></span>
                <span x-text="saveStatus"></span>
            </div>
            <div class="flex gap-2">
                <button @click="manualSave()"
                        class="px-6 py-2 bg-[#0A1018] border border-[#C9973A]/20 rounded-lg
                               text-[12px] text-[#C9973A] hover:bg-[#C9973A]/8 transition-all
                               tracking-wide">
                    Sauvegarder ✦
                </button>
                @if (!$isCompleted)
                    <button wire:click="markComplete"
                            class="px-6 py-2 bg-[#C9973A]/10 border border-[#C9973A]/30 rounded-lg
                                   text-[12px] text-[#C9973A] hover:bg-[#C9973A]/20 transition-all
                                   tracking-wide">
                        Marquer complet ✓
                    </button>
                @else
                    <span class="px-4 py-2 text-[12px] text-[#C9973A]/50 italic">
                        Jour complété ✓
                    </span>
                @endif
            </div>
        </div>

    </div>
    @endif

    @push('scripts')
    <script>
    function carnetEntry({ dayNumber, encryptedFields }) {
        return {
            dayNumber,
            fields: {
                title: '', color: '#C9973A', mood: '',
                highlight: '', reflection: '', questions: '',
                freeWriting: '', awareness: '', commitment: ''
            },
            colors: [
                { hex: '#C9973A', name: 'Or' },
                { hex: '#4A7FA5', name: 'Bleu' },
                { hex: '#7B9E6B', name: 'Vert' },
                { hex: '#A0736E', name: 'Terracotta' },
                { hex: '#8E6BAD', name: 'Violet' },
                { hex: '#4A4A5A', name: 'Ardoise' },
                { hex: '#D4A0A0', name: 'Rose' },
            ],
            moods: ['En paix', 'Joyeux·se', 'Mélancolique', 'Incertain·e', 'Puissant·e', 'Vulnérable', 'Curieux·se'],
            questions: [
                "Qu'est-ce que je refuse encore de voir en moi ?",
                "Quelle est la chose la plus difficile que j'ai traversée aujourd'hui ?",
                "Si ce jour était un enseignement, quel serait-il ?",
                "Qu'est-ce qui m'a touché·e profondément ?",
            ],
            isSaving: false,
            saveStatus: 'Toutes les modifications sont sauvegardées',
            saveTimer: null,

            async init() {
                const key = await window.JournalCrypto.getSessionKey();
                if (!key) return;
                for (const [field, blob] of Object.entries(encryptedFields)) {
                    if (blob) {
                        try {
                            this.fields[field] = await window.JournalCrypto.decrypt(blob, key);
                        } catch(e) { /* blob corrompu */ }
                    }
                }
                if (this.$refs.freeWriting) {
                    this.$refs.freeWriting.innerText = this.fields.freeWriting;
                }
            },

            appendQuestion(q) {
                const fw = this.$refs.freeWriting;
                const curr = fw.innerText.trim();
                fw.innerText = curr ? curr + '\n\n' + q + '\n' : q + '\n';
                this.fields.freeWriting = fw.innerText;
                fw.focus();
                this.debounceSave();
            },

            debounceSave() {
                clearTimeout(this.saveTimer);
                this.isSaving = true;
                this.saveStatus = 'Chiffrement en cours...';
                this.saveTimer = setTimeout(() => this.save(), 1200);
            },

            async manualSave() {
                clearTimeout(this.saveTimer);
                await this.save();
            },

            async save() {
                const key = await window.JournalCrypto.getSessionKey();
                if (!key) { this.saveStatus = 'Carnet verrouillé'; return; }

                const encrypted = {};
                for (const [field, val] of Object.entries(this.fields)) {
                    if (val) encrypted[field] = await window.JournalCrypto.encrypt(val, key);
                }

                await this.$wire.call('saveEncrypted', encrypted);
                this.isSaving   = false;
                this.saveStatus = 'Sauvegardé et chiffré ✓';
            }
        }
    }
    </script>
    @endpush
</x-app.pc-layout>
