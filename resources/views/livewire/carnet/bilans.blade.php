{{-- resources/views/livewire/carnet/bilans.blade.php --}}
<x-app.pc-layout>
    <x-slot:breadcrumb>Bilans</x-slot>

    <div class="px-6 py-8 max-w-2xl">
        <h1 class="font-serif text-[#E8D5A0] text-3xl font-light tracking-wide mb-1">
            Bilans de traversée
        </h1>
        <p class="text-sm text-[#C8C0B0]/40 italic mb-8">
            Un bilan tous les 10 jours · Bilan final à J90
        </p>

        {{-- Bilans de module --}}
        @foreach(range(1, 9) as $module)
            @php
                $review    = $moduleReviews[$module] ?? null;
                $dayNeeded = $module * 10;
                $available = $currentDay >= $dayNeeded;
                $done      = $review?->is_completed ?? false;
            @endphp

            <div class="flex items-center gap-4 card-dark p-4 mb-3
                        {{ !$available ? 'opacity-30' : '' }}">
                <div class="w-10 h-10 shrink-0 rounded-lg flex items-center justify-center
                            {{ $done ? 'bg-[#C9973A]/15' : 'bg-white/[0.03]' }}">
                    <span class="font-serif text-lg text-[#C9973A] font-light">{{ $module }}</span>
                </div>
                <div class="flex-1">
                    <p class="text-[13px] text-[#E0D5C5]">Bilan · Module {{ $module }}</p>
                    <p class="text-[11px] text-[#C8C0B0]/35 mt-0.5">
                        {{ $available ? "Disponible après J{$dayNeeded}" : "Disponible à J{$dayNeeded}" }}
                    </p>
                </div>
                @if($available)
                    <a href="{{ route('carnet.bilan', $module) }}"
                       class="btn-gold text-[11px]">
                        {{ $done ? 'Revoir' : 'Rédiger' }}
                    </a>
                @endif
                @if($done)
                    <span class="w-2 h-2 rounded-full bg-[#C9973A] shrink-0"></span>
                @endif
            </div>
        @endforeach

        {{-- Bilan final --}}
        @php $finalAvailable = $currentDay >= 90; @endphp
        <div class="mt-6 border-t border-[#C9973A]/8 pt-6">
            <div class="flex items-center gap-4 bg-[#C9973A]/5 border border-[#C9973A]/15
                        rounded-xl p-5 {{ !$finalAvailable ? 'opacity-30' : '' }}">
                <div class="w-12 h-12 shrink-0 rounded-xl bg-[#C9973A]/12 border border-[#C9973A]/25
                            flex items-center justify-center">
                    <svg class="w-5 h-5 fill-none stroke-[#C9973A] stroke-[1.5]" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                    </svg>
                </div>
                <div class="flex-1">
                    <p class="text-[14px] text-[#E8D5A0] font-medium mb-0.5">Bilan Final · 90 Jours</p>
                    <p class="text-[11px] text-[#C8C0B0]/40">
                        Votre lettre à vous-même · Votre transformation
                    </p>
                </div>
                @if($finalAvailable)
                    <a href="{{ route('carnet.bilan-final') }}" class="btn-gold">
                        Ouvrir
                    </a>
                @endif
            </div>
        </div>
    </div>
</x-app.pc-layout>
