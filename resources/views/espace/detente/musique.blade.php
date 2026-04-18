{{-- resources/views/espace/detente/musique.blade.php --}}
@extends('layouts.espace')
@section('title', 'Musique')
@section('breadcrumb', 'Musique')

@section('content')
<div class="px-6 py-8 max-w-4xl"
     x-data="musicApp()"
     x-init="init()"
     @play-track.window="loadTrack($event.detail)">

    <h1 class="font-serif text-[#E8D5A0] text-3xl font-light tracking-wide mb-1">Bibliothèque Sonore</h1>
    <p class="text-sm text-[#C8C0B0]/40 italic mb-8">Choisissez une ambiance pour accompagner votre traversée.</p>

    {{-- ── ONGLETS ── --}}
    <div class="flex gap-1 mb-8 border-b border-white/5">
        <button @click="tab = 'ambiance'"
                :class="tab === 'ambiance' ? 'text-[#C9973A] border-b-2 border-[#C9973A]' : 'text-[#C8C0B0]/40'"
                class="px-4 py-2 text-sm tracking-widest uppercase transition-colors">
            Ambiances
        </button>
        <button @click="tab = 'module'"
                :class="tab === 'module' ? 'text-[#C9973A] border-b-2 border-[#C9973A]' : 'text-[#C8C0B0]/40'"
                class="px-4 py-2 text-sm tracking-widest uppercase transition-colors">
            Modules
        </button>
        <button @click="tab = 'all'"
                :class="tab === 'all' ? 'text-[#C9973A] border-b-2 border-[#C9973A]' : 'text-[#C8C0B0]/40'"
                class="px-4 py-2 text-sm tracking-widest uppercase transition-colors">
            Toutes les pistes
        </button>
    </div>

    {{-- ── PLAYLISTS AMBIANCES ── --}}
    <div x-show="tab === 'ambiance'" x-cloak>
        @forelse($ambiancePlaylists as $playlist)
        <div class="mb-8">
            {{-- Header playlist --}}
            <div class="flex items-center justify-between mb-3">
                <div>
                    <h2 class="text-[#E8D5A0] font-serif text-lg font-light">{{ $playlist->name }}</h2>
                    @if($playlist->description)
                        <p class="text-[11px] text-[#C8C0B0]/35 italic mt-0.5">{{ $playlist->description }}</p>
                    @endif
                </div>
                <div class="flex items-center gap-2">
                    {{-- Lecture aléatoire --}}
                    <button @click="shufflePlaylist({{ $playlist->tracks->toJson() }})"
                            class="text-[11px] text-[#C8C0B0]/40 hover:text-[#C9973A] transition-colors flex items-center gap-1 px-2 py-1 border border-white/5 rounded">
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <polyline points="16 3 21 3 21 8"/><line x1="4" y1="20" x2="21" y2="3"/>
                            <polyline points="21 16 21 21 16 21"/><line x1="15" y1="15" x2="21" y2="21"/>
                        </svg>
                        Shuffle
                    </button>
                    {{-- Lire tout --}}
                    <button @click="playPlaylist({{ $playlist->tracks->toJson() }})"
                            class="text-[11px] text-[#C9973A] hover:text-[#E8D5A0] transition-colors flex items-center gap-1 px-2 py-1 border border-[#C9973A]/25 rounded">
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="currentColor"><polygon points="5,3 19,12 5,21"/></svg>
                        Lire tout
                    </button>
                </div>
            </div>

            {{-- Pistes de la playlist --}}
            <div class="space-y-1">
                @foreach($playlist->tracks as $i => $track)
                <div @click="loadTrack({ slug: '{{ $track->slug }}', title: '{{ $track->title }}', playlist: {{ $playlist->tracks->toJson() }}, index: {{ $i }} })"
                     class="group flex items-center gap-4 px-4 py-3 rounded-lg cursor-pointer transition-all border"
                     :class="currentSlug === '{{ $track->slug }}'
                             ? 'bg-[#C9973A]/10 border-[#C9973A]/25'
                             : 'bg-white/[0.02] border-white/[0.03] hover:bg-white/[0.04] hover:border-white/[0.07]'">

                    {{-- Numéro / icône lecture --}}
                    <div class="w-6 text-center shrink-0">
                        <span x-show="currentSlug !== '{{ $track->slug }}'"
                              class="text-[12px] text-[#C8C0B0]/25 group-hover:hidden">
                            {{ str_pad($i + 1, 2, '0', STR_PAD_LEFT) }}
                        </span>
                        <svg x-show="currentSlug !== '{{ $track->slug }}'"
                             class="hidden group-hover:block mx-auto"
                             width="14" height="14" viewBox="0 0 24 24" fill="#C9973A">
                             <polygon points="5,3 19,12 5,21"/>
                        </svg>
                        {{-- Barres animées si en cours --}}
                        <div x-show="currentSlug === '{{ $track->slug }}'"
                             class="flex items-end gap-0.5 h-4 justify-center">
                            @for($b = 0; $b < 3; $b++)
                                <div class="w-0.5 bg-[#C9973A] rounded-full"
                                     style="height:{{ [60,100,70][$b] }}%;
                                            animation:bar-bounce .8s ease-in-out infinite;
                                            animation-delay:{{ $b * 0.15 }}s"></div>
                            @endfor
                        </div>
                    </div>

                    {{-- Titre + infos --}}
                    <div class="flex-1 min-w-0">
                        <p class="text-[13px] truncate transition-colors"
                           :class="currentSlug === '{{ $track->slug }}' ? 'text-[#E8D5A0]' : 'text-[#C8C0B0]/70 group-hover:text-[#E0D5C5]'">
                            {{ $track->title }}
                        </p>
                        @if($track->description)
                            <p class="text-[11px] text-[#C8C0B0]/25 italic truncate mt-0.5">{{ $track->description }}</p>
                        @endif
                    </div>

                    {{-- Durée --}}
                    <span class="text-[11px] text-[#C8C0B0]/30 shrink-0">{{ $track->duration_label }}</span>
                </div>
                @endforeach
            </div>
        </div>
        @empty
            <p class="text-[#C8C0B0]/30 italic text-sm">Aucune playlist d'ambiance disponible.</p>
        @endforelse
    </div>

    {{-- ── PLAYLISTS MODULES ── --}}
    <div x-show="tab === 'module'" x-cloak>
        @forelse($modulePlaylists as $playlist)
        <div class="mb-8">
            <div class="flex items-center justify-between mb-3">
                <div>
                    <span class="text-[10px] text-[#C9973A]/60 uppercase tracking-widest">Module</span>
                    <h2 class="text-[#E8D5A0] font-serif text-lg font-light">{{ $playlist->name }}</h2>
                    @if($playlist->description)
                        <p class="text-[11px] text-[#C8C0B0]/35 italic mt-0.5">{{ $playlist->description }}</p>
                    @endif
                </div>
                <div class="flex items-center gap-2">
                    <button @click="shufflePlaylist({{ $playlist->tracks->toJson() }})"
                            class="text-[11px] text-[#C8C0B0]/40 hover:text-[#C9973A] transition-colors flex items-center gap-1 px-2 py-1 border border-white/5 rounded">
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <polyline points="16 3 21 3 21 8"/><line x1="4" y1="20" x2="21" y2="3"/>
                            <polyline points="21 16 21 21 16 21"/><line x1="15" y1="15" x2="21" y2="21"/>
                        </svg>
                        Shuffle
                    </button>
                    <button @click="playPlaylist({{ $playlist->tracks->toJson() }})"
                            class="text-[11px] text-[#C9973A] hover:text-[#E8D5A0] transition-colors flex items-center gap-1 px-2 py-1 border border-[#C9973A]/25 rounded">
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="currentColor"><polygon points="5,3 19,12 5,21"/></svg>
                        Lire tout
                    </button>
                </div>
            </div>

            <div class="space-y-1">
                @foreach($playlist->tracks as $i => $track)
                <div @click="loadTrack({ slug: '{{ $track->slug }}', title: '{{ $track->title }}', playlist: {{ $playlist->tracks->toJson() }}, index: {{ $i }} })"
                     class="group flex items-center gap-4 px-4 py-3 rounded-lg cursor-pointer transition-all border"
                     :class="currentSlug === '{{ $track->slug }}'
                             ? 'bg-[#C9973A]/10 border-[#C9973A]/25'
                             : 'bg-white/[0.02] border-white/[0.03] hover:bg-white/[0.04] hover:border-white/[0.07]'">
                    <div class="w-6 text-center shrink-0">
                        <span x-show="currentSlug !== '{{ $track->slug }}'"
                              class="text-[12px] text-[#C8C0B0]/25 group-hover:hidden">
                            {{ str_pad($i + 1, 2, '0', STR_PAD_LEFT) }}
                        </span>
                        <svg x-show="currentSlug !== '{{ $track->slug }}'"
                             class="hidden group-hover:block mx-auto"
                             width="14" height="14" viewBox="0 0 24 24" fill="#C9973A">
                             <polygon points="5,3 19,12 5,21"/>
                        </svg>
                        <div x-show="currentSlug === '{{ $track->slug }}'"
                             class="flex items-end gap-0.5 h-4 justify-center">
                            @for($b = 0; $b < 3; $b++)
                                <div class="w-0.5 bg-[#C9973A] rounded-full"
                                     style="height:{{ [60,100,70][$b] }}%;
                                            animation:bar-bounce .8s ease-in-out infinite;
                                            animation-delay:{{ $b * 0.15 }}s"></div>
                            @endfor
                        </div>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-[13px] truncate transition-colors"
                           :class="currentSlug === '{{ $track->slug }}' ? 'text-[#E8D5A0]' : 'text-[#C8C0B0]/70 group-hover:text-[#E0D5C5]'">
                            {{ $track->title }}
                        </p>
                    </div>
                    <span class="text-[11px] text-[#C8C0B0]/30 shrink-0">{{ $track->duration_label }}</span>
                </div>
                @endforeach
            </div>
        </div>
        @empty
            <p class="text-[#C8C0B0]/30 italic text-sm">Aucune playlist de module disponible.</p>
        @endforelse
    </div>

    {{-- ── TOUTES LES PISTES ── --}}
    <div x-show="tab === 'all'" x-cloak>
        <div class="flex justify-end mb-4">
            <button @click="shufflePlaylist({{ $allTracks->toJson() }})"
                    class="text-[11px] text-[#C8C0B0]/40 hover:text-[#C9973A] transition-colors flex items-center gap-1 px-3 py-1.5 border border-white/5 rounded">
                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <polyline points="16 3 21 3 21 8"/><line x1="4" y1="20" x2="21" y2="3"/>
                    <polyline points="21 16 21 21 16 21"/><line x1="15" y1="15" x2="21" y2="21"/>
                </svg>
                Shuffle tout
            </button>
        </div>
        <div class="space-y-1">
            @foreach($allTracks as $i => $track)
            <div @click="loadTrack({ slug: '{{ $track->slug }}', title: '{{ $track->title }}', playlist: {{ $allTracks->toJson() }}, index: {{ $i }} })"
                 class="group flex items-center gap-4 px-4 py-3 rounded-lg cursor-pointer transition-all border"
                 :class="currentSlug === '{{ $track->slug }}'
                         ? 'bg-[#C9973A]/10 border-[#C9973A]/25'
                         : 'bg-white/[0.02] border-white/[0.03] hover:bg-white/[0.04]'">
                <div class="w-6 text-center shrink-0">
                    <span x-show="currentSlug !== '{{ $track->slug }}'"
                          class="text-[12px] text-[#C8C0B0]/25 group-hover:hidden">
                        {{ str_pad($i + 1, 2, '0', STR_PAD_LEFT) }}
                    </span>
                    <svg x-show="currentSlug !== '{{ $track->slug }}'"
                         class="hidden group-hover:block mx-auto"
                         width="14" height="14" viewBox="0 0 24 24" fill="#C9973A">
                         <polygon points="5,3 19,12 5,21"/>
                    </svg>
                    <div x-show="currentSlug === '{{ $track->slug }}'"
                         class="flex items-end gap-0.5 h-4 justify-center">
                        @for($b = 0; $b < 3; $b++)
                            <div class="w-0.5 bg-[#C9973A] rounded-full"
                                 style="height:{{ [60,100,70][$b] }}%;
                                        animation:bar-bounce .8s ease-in-out infinite;
                                        animation-delay:{{ $b * 0.15 }}s"></div>
                        @endfor
                    </div>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-[13px] truncate transition-colors"
                       :class="currentSlug === '{{ $track->slug }}' ? 'text-[#E8D5A0]' : 'text-[#C8C0B0]/70 group-hover:text-[#E0D5C5]'">
                        {{ $track->title }}
                    </p>
                    <div class="flex items-center gap-2 mt-0.5">
                        @if($track->category)
                            <span class="text-[10px] text-[#C8C0B0]/25 capitalize">{{ $track->category }}</span>
                        @endif
                        @if($track->module)
                            <span class="text-[10px] text-[#C9973A]/35 capitalize">· {{ $track->module }}</span>
                        @endif
                    </div>
                </div>
                <span class="text-[11px] text-[#C8C0B0]/30 shrink-0">{{ $track->duration_label }}</span>
            </div>
            @endforeach
        </div>
    </div>
</div>

{{-- Mini player --}}
@include('partials.mini-player')

<style>
@keyframes bar-bounce {
    0%,100% { transform: scaleY(.4); }
    50%      { transform: scaleY(1); }
}
[x-cloak] { display: none !important; }
</style>

{{-- Alpine music app --}}
<script>
function musicApp() {
    return {
        tab:         'ambiance',
        currentSlug: null,
        playlist:    [],
        currentIndex: 0,

        init() {
            // Écouter les événements du mini-player (piste suivante/précédente)
            window.addEventListener('player-next',     () => this.playNext());
            window.addEventListener('player-prev',     () => this.playPrev());
            window.addEventListener('player-ended',    () => this.playNext());
            window.addEventListener('track-changed',   e  => this.currentSlug = e.detail.slug);
        },

        loadTrack(detail) {
            this.currentSlug  = detail.slug;
            this.playlist     = detail.playlist  || [];
            this.currentIndex = detail.index      ?? 0;
            // Envoyer au mini-player
            window.dispatchEvent(new CustomEvent('play-track', { detail }));
        },

        playPlaylist(tracks) {
            if (!tracks.length) return;
            this.playlist     = tracks;
            this.currentIndex = 0;
            this.loadTrack({ slug: tracks[0].slug, title: tracks[0].title, playlist: tracks, index: 0 });
        },

        shufflePlaylist(tracks) {
            if (!tracks.length) return;
            const shuffled = [...tracks].sort(() => Math.random() - 0.5);
            this.playlist     = shuffled;
            this.currentIndex = 0;
            this.loadTrack({ slug: shuffled[0].slug, title: shuffled[0].title, playlist: shuffled, index: 0 });
        },

        playNext() {
            if (!this.playlist.length) return;
            this.currentIndex = (this.currentIndex + 1) % this.playlist.length;
            const track = this.playlist[this.currentIndex];
            this.loadTrack({ slug: track.slug, title: track.title, playlist: this.playlist, index: this.currentIndex });
        },

        playPrev() {
            if (!this.playlist.length) return;
            this.currentIndex = (this.currentIndex - 1 + this.playlist.length) % this.playlist.length;
            const track = this.playlist[this.currentIndex];
            this.loadTrack({ slug: track.slug, title: track.title, playlist: this.playlist, index: this.currentIndex });
        },
    }
}
</script>
@endsection