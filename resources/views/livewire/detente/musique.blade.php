{{-- resources/views/livewire/detente/musique.blade.php --}}
<x-app.pc-layout>
    <x-slot:breadcrumb>Détente · Musique</x-slot>

    <div class="px-6 py-8 max-w-3xl">

        <h1 class="font-serif text-[#E8D5A0] text-3xl font-light tracking-wide mb-1">
            Bibliothèque Sonore
        </h1>
        <p class="text-sm text-[#C8C0B0]/40 italic mb-8">
            Choisissez une ambiance pour accompagner votre traversée.
        </p>

        {{-- Filtres catégories --}}
        <div class="flex flex-wrap gap-2 mb-6">
            @foreach($categories as $cat)
                <button wire:click="setFilter('{{ $cat }}')"
                        class="px-3.5 py-1.5 text-[11px] rounded-full border transition-all capitalize
                               {{ $filterCat === $cat
                                   ? 'bg-[#C9973A]/15 border-[#C9973A]/40 text-[#C9973A]'
                                   : 'bg-white/[0.02] border-[#C9973A]/12 text-[#C8C0B0]/40
                                      hover:border-[#C9973A]/30 hover:text-[#C9973A]/70' }}">
                    {{ $cat }}
                </button>
            @endforeach
        </div>

        {{-- Piste active --}}
        @if($activeSlug)
            <div class="flex items-center gap-4 bg-[#C9973A]/8 border border-[#C9973A]/25
                        rounded-xl p-4 mb-6">
                {{-- Visualiseur animé --}}
                <div class="flex items-end gap-0.5 h-8 w-10 shrink-0">
                    @for($i = 0; $i < 6; $i++)
                        <div class="flex-1 bg-[#C9973A] rounded-full"
                             style="height:{{ 30 + ($i * 12) }}%;
                                    animation: wave {{ 0.6 + $i * 0.15 }}s ease-in-out infinite alternate">
                        </div>
                    @endfor
                </div>
                <div class="flex-1">
                    <p class="text-[14px] text-[#E8D5A0]">{{ $activeTitle }}</p>
                    <p class="text-[11px] text-[#C9973A]/50 mt-0.5">En cours de lecture</p>
                </div>
                <button wire:click="selectTrack('')"
                        class="text-[#C8C0B0]/30 hover:text-[#C8C0B0]/60 transition-colors">
                    <svg class="w-4 h-4 fill-none stroke-current stroke-[1.5]" viewBox="0 0 16 16">
                        <path stroke-linecap="round" d="M4 4l8 8M12 4l-8 8"/>
                    </svg>
                </button>
            </div>
        @endif

        {{-- Grille des pistes --}}
        <div class="grid grid-cols-2 gap-3">
            @forelse($filteredTracks as $track)
                <div wire:click="selectTrack('{{ $track['slug'] }}')"
                     class="group card-dark p-5 cursor-pointer transition-all duration-200
                            {{ $activeSlug === $track['slug']
                                ? 'border-[#C9973A]/35 bg-[#C9973A]/6'
                                : 'hover:border-[#C9973A]/20 hover:bg-white/[0.03]' }}">

                    {{-- Visualiseur --}}
                    <div class="flex items-end gap-0.5 h-7 mb-4">
                        @for($i = 0; $i < 10; $i++)
                            <div class="flex-1 rounded-full transition-all duration-300
                                        {{ $activeSlug === $track['slug']
                                            ? 'bg-[#C9973A]'
                                            : 'bg-[#C9973A]/20 group-hover:bg-[#C9973A]/35' }}"
                                 style="height: {{ [25,60,40,80,55,35,70,45,65,30][$i] }}%;
                                        {{ $activeSlug === $track['slug']
                                            ? "animation: wave ".( 0.5 + $i * 0.1 )."s ease-in-out infinite alternate"
                                            : '' }}">
                            </div>
                        @endfor
                    </div>

                    {{-- Infos --}}
                    <p class="text-[14px] mb-1 transition-colors
                               {{ $activeSlug === $track['slug']
                                   ? 'text-[#E8D5A0]'
                                   : 'text-[#C8C0B0]/70 group-hover:text-[#E0D5C5]' }}">
                        {{ $track['title'] }}
                    </p>

                    <div class="flex items-center justify-between">
                        <span class="text-[11px] text-[#C8C0B0]/30">
                            {{ $track['duration_label'] }}
                        </span>
                        <span class="text-[10px] px-2 py-0.5 rounded-full capitalize
                                     bg-[#C9973A]/8 border border-[#C9973A]/12 text-[#C9973A]/50">
                            {{ $track['category'] }}
                        </span>
                    </div>

                    {{-- Icône play --}}
                    <div class="mt-3 flex items-center gap-1.5
                                {{ $activeSlug === $track['slug'] ? 'opacity-100' : 'opacity-0 group-hover:opacity-100' }}
                                transition-opacity">
                        <svg class="w-3 h-3 fill-[#C9973A]" viewBox="0 0 12 12">
                            <path d="M3 2l7 4-7 4V2z"/>
                        </svg>
                        <span class="text-[11px] text-[#C9973A]/70">
                            {{ $activeSlug === $track['slug'] ? 'En écoute' : 'Écouter' }}
                        </span>
                    </div>
                </div>
            @empty
                <div class="col-span-2 py-12 text-center">
                    <p class="text-[13px] text-[#C8C0B0]/30 italic">
                        Aucune piste dans cette catégorie.
                    </p>
                </div>
            @endforelse
        </div>
    </div>

    <style>
    @keyframes wave {
        from { transform: scaleY(0.3); }
        to   { transform: scaleY(1); }
    }
    </style>

    @push('scripts')
    <script>
    window.addEventListener('play-track', (e) => {
        // Log automatique après 30 secondes d'écoute
        setTimeout(() => {
            @this.call('logListen', e.detail.slug, 30);
        }, 30000);
    });
    </script>
    @endpush
</x-app.pc-layout>
