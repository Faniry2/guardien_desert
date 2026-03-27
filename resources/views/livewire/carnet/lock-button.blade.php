{{-- resources/views/livewire/carnet/lock-button.blade.php --}}
<button wire:click="lock"
        class="flex items-center gap-2 px-3.5 py-1.5 text-[11px]
               bg-white/[0.02] border border-white/[0.06] rounded-lg
               text-[#C8C0B0]/35 hover:text-[#C8C0B0]/60
               hover:border-white/[0.12] transition-all tracking-wide">
    <svg class="w-3 h-3 fill-none stroke-current stroke-[1.5]" viewBox="0 0 16 16">
        <rect x="3" y="7" width="10" height="8" rx="1.5"/>
        <path stroke-linecap="round" d="M5 7V5a3 3 0 016 0v2"/>
    </svg>
    Verrouiller
</button>
