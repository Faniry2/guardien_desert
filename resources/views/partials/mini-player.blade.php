{{-- resources/views/partials/mini-player.blade.php --}}
<div x-data="miniPlayer()"
     @play-track.window="play($event.detail.slug, $event.detail.title)"
     x-show="track"
     x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="opacity-0 translate-y-2"
     x-transition:enter-end="opacity-100 translate-y-0"
     class="fixed bottom-0 right-0 z-40 bg-[#0A1018]/95 border-t border-[#C9973A]/10
            px-6 py-3 flex items-center gap-4"
     style="left:260px;display:none">

    <div class="flex-1 min-w-0">
        <p class="text-[13px] text-[#E0D5C5] truncate" x-text="title"></p>
        <p class="text-[10px] text-[#C8C0B0]/35">Espace Détente</p>
    </div>

    <div class="w-24 h-0.5 bg-white/[0.06] rounded-full cursor-pointer relative" @click="seek($event)">
        <div class="h-full bg-[#C9973A] rounded-full transition-all" :style="`width:${progress}%`"></div>
    </div>

    <button @click="toggle()"
            class="w-8 h-8 rounded-full border border-[#C9973A]/25 flex items-center justify-center
                   text-[#C9973A] hover:bg-[#C9973A]/10 transition-all">
        <svg class="w-3.5 h-3.5 fill-[#C9973A]" viewBox="0 0 16 16">
            <path x-show="!playing" d="M5 3l8 5-8 5V3z"/>
            <path x-show="playing" d="M5 3h2v10H5zM9 3h2v10H9z"/>
        </svg>
    </button>

    <button @click="close()" class="w-6 h-6 flex items-center justify-center text-[#C8C0B0]/30 hover:text-[#C8C0B0]/60">
        <svg class="w-3 h-3 fill-none stroke-current stroke-2" viewBox="0 0 12 12">
            <path stroke-linecap="round" d="M1 1l10 10M11 1L1 11"/>
        </svg>
    </button>
</div>

@once
@push('scripts')
<script>
function miniPlayer() {
    return {
        track: null, title: '', playing: false, progress: 0,
        audio: new Audio(), interval: null,
        play(slug, title) {
            this.audio.pause();
            this.track = slug; this.title = title;
            this.audio.src = `/espace/detente/audio/${slug}`;
            this.audio.play().catch(() => {});
            this.playing = true;
            clearInterval(this.interval);
            this.interval = setInterval(() => {
                if (this.audio.duration) this.progress = Math.round((this.audio.currentTime / this.audio.duration) * 100);
            }, 500);
        },
        toggle() { this.playing ? this.audio.pause() : this.audio.play(); this.playing = !this.playing; },
        seek(e) {
            const r = e.currentTarget.getBoundingClientRect();
            this.audio.currentTime = ((e.clientX - r.left) / r.width) * this.audio.duration;
        },
        close() { this.audio.pause(); this.playing = false; this.track = null; clearInterval(this.interval); }
    }
}
</script>
@endpush
@endonce
