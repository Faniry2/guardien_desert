{{-- resources/views/espace/detente/musique.blade.php --}}
@extends('layouts.espace')
@section('title', 'Musique')
@section('breadcrumb', 'Musique')

@section('content')
<div class="px-6 py-8 max-w-3xl" x-data="{ activeSlug: '', activeTitle: '' }">

    <h1 class="font-serif text-[#E8D5A0] text-3xl font-light tracking-wide mb-1">Bibliothèque Sonore</h1>
    <p class="text-sm text-[#C8C0B0]/40 italic mb-8">Choisissez une ambiance pour accompagner votre traversée.</p>

    {{-- Piste active --}}
    <div x-show="activeSlug" class="flex items-center gap-4 bg-[#C9973A]/8 border border-[#C9973A]/25 rounded-xl p-4 mb-6" style="display:none">
        <div class="flex items-end gap-0.5 h-8 w-10 shrink-0">
            @for($i = 0; $i < 6; $i++)
                <div class="flex-1 bg-[#C9973A] rounded-full animate-pulse"
                     style="height:{{ [40,70,50,90,60,35][$i] }}%;animation-delay:{{ $i * 0.1 }}s"></div>
            @endfor
        </div>
        <div class="flex-1">
            <p class="text-[14px] text-[#E8D5A0]" x-text="activeTitle"></p>
            <p class="text-[11px] text-[#C9973A]/50 mt-0.5">En cours de lecture</p>
        </div>
    </div>

    {{-- Grille pistes --}}
    <div class="grid grid-cols-2 gap-3">
        @foreach($tracks as $track)
            <div @click="activeSlug = '{{ $track['slug'] }}'; activeTitle = '{{ $track['title'] }}'; $dispatch('play-track', { slug: '{{ $track['slug'] }}', title: '{{ $track['title'] }}' })"
                 class="group bg-white/[0.02] border border-[#C9973A]/8 rounded-xl p-5 cursor-pointer
                        hover:border-[#C9973A]/20 hover:bg-white/[0.03] transition-all"
                 :class="activeSlug === '{{ $track['slug'] }}' ? 'border-[#C9973A]/35 bg-[#C9973A]/6' : ''">

                <div class="flex items-end gap-0.5 h-7 mb-4">
                    @for($i = 0; $i < 10; $i++)
                        <div class="flex-1 rounded-full transition-all group-hover:bg-[#C9973A]/35"
                             :class="activeSlug === '{{ $track['slug'] }}' ? 'bg-[#C9973A]' : 'bg-[#C9973A]/20'"
                             style="height:{{ [25,60,40,80,55,35,70,45,65,30][$i] }}%"></div>
                    @endfor
                </div>

                <p class="text-[14px] text-[#C8C0B0]/70 group-hover:text-[#E0D5C5] mb-1 transition-colors"
                   :class="activeSlug === '{{ $track['slug'] }}' ? 'text-[#E8D5A0]' : ''">
                    {{ $track['title'] }}
                </p>
                <div class="flex items-center justify-between">
                    <span class="text-[11px] text-[#C8C0B0]/30">{{ $track['duration_label'] }}</span>
                    <span class="text-[10px] px-2 py-0.5 rounded-full bg-[#C9973A]/8 border border-[#C9973A]/12 text-[#C9973A]/50 capitalize">
                        {{ $track['category'] }}
                    </span>
                </div>
            </div>
        @endforeach
    </div>
</div>

{{-- Mini player --}}
@include('partials.mini-player')
@endsection
