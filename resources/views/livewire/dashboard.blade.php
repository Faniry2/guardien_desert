{{-- resources/views/livewire/dashboard.blade.php --}}
<x-app-layout>
    <x-slot:breadcrumb>Tableau de bord</x-slot>

    <div class="px-6 py-8 max-w-3xl">

        {{-- Greeting --}}
        <h1 class="font-serif text-[#E8D5A0] text-3xl font-light tracking-wide mb-1">
            Bonjour, {{ auth()->user()->firstName() }}.
        </h1>
        <p class="text-sm text-[#C8C0B0]/40 italic mb-8">
            Jour {{ $dayNumber }} de votre traversée · Bienvenue dans votre espace.
        </p>

        {{-- Stats cards --}}
        <div class="grid grid-cols-3 gap-3 mb-6">
            <div class="bg-white/[0.03] border border-[#C9973A]/10 rounded-[10px] p-4">
                <p class="font-serif text-[#C9973A] text-3xl font-light leading-none mb-1">
                    {{ $dayNumber }}
                </p>
                <p class="text-[11px] text-[#C8C0B0]/40 tracking-wide">Jours écrits</p>
            </div>
            <div class="bg-white/[0.03] border border-[#C9973A]/10 rounded-[10px] p-4">
                <p class="font-serif text-[#C9973A] text-3xl font-light leading-none mb-1">
                    {{ 90 - $dayNumber }}
                </p>
                <p class="text-[11px] text-[#C8C0B0]/40 tracking-wide">Jours restants</p>
            </div>
            <div class="bg-white/[0.03] border border-[#C9973A]/10 rounded-[10px] p-4">
                <p class="font-serif text-[#C9973A] text-3xl font-light leading-none mb-1">
                    Mod. {{ $module }}
                </p>
                <p class="text-[11px] text-[#C8C0B0]/40 tracking-wide">Module actuel</p>
            </div>
        </div>

        {{-- CTA Jour actuel --}}
        <div class="flex items-center gap-4 bg-white/[0.02] border border-[#C9973A]/8
                    rounded-[10px] p-4 mb-6">
            <p class="font-serif text-[#C9973A] text-4xl font-light leading-none min-w-14">
                {{ $dayNumber }}
            </p>
            <div class="flex-1">
                <p class="text-[15px] text-[#E0D5C5] mb-1">Reprendre l'écriture du jour</p>
                <p class="text-[11px] text-[#C8C0B0]/35 tracking-wide">
                    {{ now()->isoFormat('dddd D MMMM YYYY') }} · Module {{ $module }}, Jour {{ (($dayNumber - 1) % 10) + 1 }}
                </p>
            </div>
            <a href="{{ route('espace.carnet.day', $dayNumber) }}"
               class="px-4 py-2 bg-[#C9973A]/10 border border-[#C9973A]/20 rounded-lg
                      text-[12px] text-[#C9973A] hover:bg-[#C9973A]/18 whitespace-nowrap
                      transition-all">
                Ouvrir le carnet
            </a>
        </div>

        {{-- Module track --}}
        <div class="flex gap-1.5 mb-2">
            @for ($i = 1; $i <= 20; $i++)
                <div class="flex-1 h-1.5 rounded-full
                    {{ $i <= $dayNumber - 1
                        ? 'bg-[#C9973A]'
                        : ($i === $dayNumber ? 'bg-[#C9973A]/40' : 'bg-white/[0.04]') }}">
                </div>
            @endfor
        </div>
        <div class="flex justify-between text-[10px] text-[#C8C0B0]/30 tracking-wide mb-6">
            <span>Mod. 1 ✓</span>
            <span class="text-[#C9973A]/60">Mod. 2 →</span>
            <span>Mod. 3</span>
        </div>

        {{-- Dernières entrées --}}
        <div class="bg-white/[0.02] border border-[#C9973A]/8 rounded-[10px] overflow-hidden">
            <p class="px-4 py-3 text-[11px] tracking-widest uppercase text-[#C9973A]/50
                      border-b border-[#C9973A]/6">
                Dernières entrées
            </p>
            @forelse ($recentEntries as $entry)
                <a href="{{ route('espace.carnet.day', $entry->day_number) }}"
                   class="flex items-center gap-3 px-4 py-3
                          border-b border-white/[0.03] last:border-0
                          hover:bg-[#C9973A]/[0.04] transition-colors">
                    <span class="font-serif text-[#C9973A] text-base font-light min-w-7">
                        {{ $entry->day_number }}
                    </span>
                    <span class="flex-1">
                        <span class="block text-[13px] text-[#C8C0B0]/70">
                            {{ $entry->decryptedTitle() ?? 'Entrée chiffrée' }}
                        </span>
                        <span class="block text-[11px] text-[#C8C0B0]/30 mt-0.5">
                            {{ $entry->entry_date->isoFormat('D MMMM') }} · Module {{ ceil($entry->day_number / 10) }}
                        </span>
                    </span>
                    <span class="w-2 h-2 rounded-full
                        {{ $entry->is_completed ? 'bg-[#C9973A]' : 'bg-[#C9973A]/30' }}">
                    </span>
                </a>
            @empty
                <p class="px-4 py-6 text-[13px] text-[#C8C0B0]/30 italic text-center">
                    Votre traversée commence maintenant.
                </p>
            @endforelse
        </div>
    </div>
</x-app-layout>
