{{-- resources/views/livewire/detente/index.blade.php --}}
<x-app.pc-layout>
    <x-slot:breadcrumb>Espace détente</x-slot>

    <div class="px-6 py-8 max-w-3xl">
        <h1 class="font-serif text-[#E8D5A0] text-3xl font-light tracking-wide mb-1">
            Espace Détente
        </h1>
        <p class="text-sm text-[#C8C0B0]/40 italic mb-8">
            Posez-vous. Respirez. Laissez venir.
        </p>

        {{-- Catégories --}}
        <div class="flex gap-2 mb-6">
            @foreach(['Tous', 'Nature', 'Méditation', 'Ambiance', 'Respiration'] as $cat)
                <button onclick="filterCat('{{ strtolower($cat) }}')"
                        class="px-3.5 py-1.5 text-[11px] rounded-full border transition-all
                               border-[#C9973A]/15 text-[#C8C0B0]/50
                               hover:border-[#C9973A]/35 hover:text-[#C9973A]">
                    {{ $cat }}
                </button>
            @endforeach
        </div>

        {{-- Grille de pistes --}}
        <div class="grid grid-cols-2 gap-3">
            @foreach($tracks as $track)
                <div class="card-dark p-5 cursor-pointer hover:border-[#C9973A]/20 transition-all"
                     onclick="playTrack('{{ $track['slug'] }}', '{{ $track['title'] }}')">

                    {{-- Visualiseur animé --}}
                    <div class="flex items-end gap-0.5 h-8 mb-4">
                        @for($i = 0; $i < 12; $i++)
                            <div class="flex-1 bg-[#C9973A]/20 rounded-full transition-all"
                                 style="height: {{ rand(20, 100) }}%; animation: wave {{ 0.8 + $i * 0.1 }}s ease-in-out infinite alternate;">
                            </div>
                        @endfor
                    </div>

                    <p class="text-[14px] text-[#E0D5C5] mb-1">{{ $track['title'] }}</p>
                    <div class="flex items-center justify-between">
                        <p class="text-[11px] text-[#C8C0B0]/35">{{ $track['duration_label'] }}</p>
                        <span class="text-[10px] px-2 py-0.5 rounded-full bg-[#C9973A]/8
                                     border border-[#C9973A]/15 text-[#C9973A]/60">
                            {{ $track['category'] }}
                        </span>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <style>
    @keyframes wave {
        from { transform: scaleY(0.4); }
        to   { transform: scaleY(1); }
    }
    </style>

    @push('scripts')
    <script>
    function playTrack(slug, title) {
        window.dispatchEvent(new CustomEvent('play-track', {
            detail: { slug, title }
        }));
    }
    </script>
    @endpush
</x-app.pc-layout>
