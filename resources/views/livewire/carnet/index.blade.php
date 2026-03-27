{{-- resources/views/livewire/carnet/index.blade.php --}}
<x-app.pc-layout>
    <x-slot:breadcrumb>Mon carnet</x-slot>

    <div class="px-6 py-8 max-w-3xl">
        <h1 class="font-serif text-[#E8D5A0] text-3xl font-light tracking-wide mb-1">
            Mon Carnet de Traversée
        </h1>
        <p class="text-sm text-[#C8C0B0]/40 italic mb-8">
            90 jours · 90 pages de transformation
        </p>

        {{-- Modules --}}
        @foreach(range(1, 9) as $module)
            @php
                $startDay = ($module - 1) * 10 + 1;
                $endDay   = $module * 10;
            @endphp

            <div class="mb-6">
                <div class="flex items-center gap-3 mb-3">
                    <span class="text-[10px] tracking-widest uppercase text-[#C9973A]/50">
                        Module {{ $module }}
                    </span>
                    <div class="flex-1 h-px bg-[#C9973A]/8"></div>
                    @if($completedModules[$module] ?? false)
                        <span class="text-[10px] text-[#C9973A]/50">✓ Bilan disponible</span>
                    @endif
                </div>

                <div class="grid grid-cols-5 gap-2">
                    @for($day = $startDay; $day <= $endDay; $day++)
                        @php
                            $entry    = $entries[$day] ?? null;
                            $done     = $entry?->is_completed ?? false;
                            $isCurrent = $day === $currentDay;
                            $isFuture  = $day > $currentDay;
                        @endphp
                        <a href="{{ $isFuture ? '#' : route('carnet.day', $day) }}"
                           class="relative flex flex-col items-center justify-center
                                  rounded-lg border py-3 transition-all duration-150
                                  {{ $isCurrent
                                      ? 'bg-[#C9973A]/12 border-[#C9973A]/40'
                                      : ($done
                                          ? 'bg-[#C9973A]/6 border-[#C9973A]/15 hover:bg-[#C9973A]/10'
                                          : ($isFuture
                                              ? 'bg-white/[0.01] border-white/[0.04] cursor-not-allowed opacity-40'
                                              : 'bg-white/[0.02] border-[#C9973A]/8 hover:bg-[#C9973A]/6')) }}">

                            {{-- Indicateur complété --}}
                            @if($done)
                                <span class="absolute top-1.5 right-1.5 w-1.5 h-1.5 rounded-full bg-[#C9973A]"></span>
                            @endif

                            <span class="font-serif text-lg font-light leading-none mb-0.5
                                {{ $isCurrent ? 'text-[#C9973A]' : ($done ? 'text-[#C9973A]/70' : 'text-[#C8C0B0]/30') }}">
                                {{ $day }}
                            </span>
                            <span class="text-[9px] tracking-wide
                                {{ $isCurrent ? 'text-[#C9973A]/60' : 'text-[#C8C0B0]/20' }}">
                                J{{ $day }}
                            </span>
                        </a>
                    @endfor
                </div>
            </div>
        @endforeach
    </div>
</x-app.pc-layout>
