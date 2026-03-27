{{-- resources/views/espace/detente/index.blade.php --}}
@extends('layouts.espace')
@section('title', 'Espace Détente')
@section('breadcrumb', 'Espace détente')

@section('content')
<div class="px-6 py-8 max-w-3xl">
    <h1 class="font-serif text-[#E8D5A0] text-3xl font-light tracking-wide mb-1">Espace Détente</h1>
    <p class="text-sm text-[#C8C0B0]/40 italic mb-8">Posez-vous. Respirez. Laissez venir.</p>

    <div class="grid grid-cols-2 gap-4 mb-10">
        <a href="{{ route('detente.musique') }}"
           class="bg-white/[0.02] border border-[#C9973A]/10 rounded-xl p-6 hover:border-[#C9973A]/25 transition-all group">
            <div class="flex items-end gap-0.5 h-8 mb-4">
                @for($i = 0; $i < 10; $i++)
                    <div class="flex-1 bg-[#C9973A]/25 group-hover:bg-[#C9973A]/40 rounded-full transition-all"
                         style="height:{{ [30,60,45,80,55,35,70,40,65,25][$i] }}%"></div>
                @endfor
            </div>
            <p class="text-[15px] text-[#E0D5C5] mb-1">Bibliothèque Sonore</p>
            <p class="text-[11px] text-[#C8C0B0]/35">{{ count($tracks) }} pistes · Méditation & Nature</p>
        </a>

        <a href="{{ route('detente.meditations') }}"  class="bg-white/[0.02] border border-[#C9973A]/10 rounded-xl p-6">
            <div class="w-10 h-10 rounded-full bg-[#C9973A]/10 border border-[#C9973A]/20
                        flex items-center justify-center mb-4">
                <svg class="w-5 h-5 fill-none stroke-[#C9973A] stroke-[1.5]" viewBox="0 0 24 24">
                    <circle cx="12" cy="12" r="10"/>
                    <path d="M10 8l6 4-6 4V8z" fill="#C9973A" stroke="none"/>
                </svg>
            </div>
            <p class="text-[15px] text-[#E0D5C5] mb-1">Méditations guidées</p>
            <p class="text-[11px] text-[#C8C0B0]/35">{{ count($meditations) }} méditation(s) disponible(s)</p>
        </a>
    </div>

    {{-- Section méditations --}}
    <div class="flex items-center gap-3 mb-5">
        <span class="text-[10px] tracking-widest uppercase text-[#C9973A]/50">Méditations guidées</span>
        <div class="flex-1 h-px bg-[#C9973A]/8"></div>
    </div>

    <div class="space-y-3" x-data="{ active: null, playing: false }">

        @forelse($meditations as $index => $med)
            <div class="bg-white/[0.02] border border-[#C9973A]/8 rounded-xl overflow-hidden transition-all"
                 :class="active === '{{ $med['slug'] }}' ? 'border-[#C9973A]/30' : ''">

                {{-- Header cliquable --}}
                <div class="flex items-center gap-4 p-4 cursor-pointer select-none"
                     @click="active === '{{ $med['slug'] }}' ? active = null : active = '{{ $med['slug'] }}'">

                    {{-- Bouton play --}}
                    <div class="w-10 h-10 shrink-0 rounded-full border flex items-center justify-center transition-all"
                         :class="active === '{{ $med['slug'] }}'
                             ? 'bg-[#C9973A] border-[#C9973A]'
                             : 'bg-white/[0.03] border-[#C9973A]/20 hover:border-[#C9973A]/50'">
                        <svg class="w-4 h-4" viewBox="0 0 16 16">
                            <path x-show="active !== '{{ $med['slug'] }}'"
                                  d="M5 3l8 5-8 5V3z" fill="#C9973A"/>
                            <path x-show="active === '{{ $med['slug'] }}'"
                                  d="M5 3l8 5-8 5V3z" fill="#0A1018"/>
                        </svg>
                    </div>

                    <div class="flex-1 min-w-0">
                        <p class="text-[14px] text-[#E0D5C5] mb-0.5">{{ $med['title'] }}</p>
                        <div class="flex items-center gap-2">
                            <span class="text-[11px] text-[#C8C0B0]/35">{{ $med['duration'] }}</span>
                            <span class="w-1 h-1 rounded-full bg-[#C8C0B0]/20"></span>
                            <span class="text-[10px] px-2 py-0.5 rounded-full border border-[#C9973A]/15
                                         text-[#C9973A]/50" style="background:rgba(201,151,58,0.06)">
                                {{ $med['type'] === 'video' ? '🎬 Vidéo' : '🎧 Audio' }}
                            </span>
                        </div>
                    </div>

                    {{-- Chevron --}}
                    <svg class="w-4 h-4 fill-none stroke-[#C9973A]/40 stroke-[1.5] transition-transform duration-200"
                         :class="active === '{{ $med['slug'] }}' ? 'rotate-180' : ''"
                         viewBox="0 0 16 16">
                        <path stroke-linecap="round" d="M4 6l4 4 4-4"/>
                    </svg>
                </div>

                {{-- Player --}}
                <div x-show="active === '{{ $med['slug'] }}'"
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0"
                     x-transition:enter-end="opacity-100"
                     class="px-4 pb-5">

                    @if($med['type'] === 'video')
                        <div class="rounded-xl overflow-hidden bg-black" style="aspect-ratio:16/9">
                            @if(str_contains($med['src'], 'youtube.com') || str_contains($med['src'], 'youtu.be'))
                                <iframe
                                    :src="active === '{{ $med['slug'] }}' ? '{{ $med['embed'] ?? $med['src'] }}&autoplay=1&rel=0' : ''"
                                    class="w-full h-full"
                                    frameborder="0"
                                    allow="accelerometer; autoplay; clipboard-write; encrypted-media"
                                    allowfullscreen>
                                </iframe>
                            @elseif(str_contains($med['src'], 'vimeo.com'))
                                <iframe
                                    :src="active === '{{ $med['slug'] }}' ? '{{ $med['embed'] ?? $med['src'] }}?autoplay=1' : ''"
                                    class="w-full h-full"
                                    frameborder="0"
                                    allow="autoplay; fullscreen"
                                    allowfullscreen>
                                </iframe>
                            @else
                                <video controls class="w-full h-full rounded-xl">
                                    <source src="{{ $med['src'] }}" type="video/mp4" />
                                </video>
                            @endif
                        </div>
                    @else
                        <audio controls class="w-full rounded-lg">
                            <source src="{{ $med['src'] }}" type="audio/mpeg" />
                        </audio>
                    @endif

                    @if(isset($med['description']))
                        <p class="text-[12px] text-[#C8C0B0]/40 italic mt-3 leading-relaxed">
                            {{ $med['description'] }}
                        </p>
                    @endif
                </div>
            </div>
        @empty
            <div class="bg-white/[0.02] border border-[#C9973A]/8 rounded-xl p-10 text-center">
                <p class="font-serif text-[#E0D5C5]/40 text-lg font-light mb-2">Aucune méditation disponible</p>
                <p class="text-[12px] text-[#C8C0B0]/25 italic">Revenez bientôt.</p>
            </div>
        @endforelse
    </div>
</div>
@endsection