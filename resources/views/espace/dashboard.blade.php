{{-- resources/views/espace/dashboard.blade.php --}}
@extends('layouts.espace')

@section('title', 'Tableau de bord')
@section('breadcrumb', 'Tableau de bord')

@section('content')
<div class="px-6 py-8 max-w-3xl">

    <h1 class="font-serif text-[#E8D5A0] text-3xl font-light tracking-wide mb-1">
        Bonjour, {{ auth()->user()->prenom ?? auth()->user()->name }}.
    </h1>
    <p class="text-sm text-[#C8C0B0]/40 italic mb-8">
        @if($carnet)
            Jour {{ $dayNumber }} de votre traversée · Bienvenue dans votre espace.
        @else
            Bienvenue dans votre espace. Configurez votre carnet pour commencer.
        @endif
    </p>

    @if(!$carnet)
        {{-- Pas de carnet --}}
        <div class="bg-[#C9973A]/5 border border-[#C9973A]/20 rounded-xl p-8 text-center mb-8">
            <p class="font-serif text-[#E8D5A0] text-xl font-light mb-3">Votre carnet n'est pas encore créé</p>
            <p class="text-sm text-[#C8C0B0]/40 mb-6 italic">Commencez votre traversée de 90 jours</p>
            <a href="{{ route('carnet.setup') }}"
               class="inline-block px-8 py-3 bg-[#C9973A] text-[#0A1018] rounded-xl
                      text-[13px] tracking-wide hover:bg-[#E8C47A] transition-all">
                ✦ Configurer mon carnet
            </a>
        </div>
    @else
        {{-- Stats --}}
        <div class="grid grid-cols-3 gap-3 mb-6">
            <div class="bg-white/[0.03] border border-[#C9973A]/10 rounded-[10px] p-4">
                <p class="font-serif text-[#C9973A] text-3xl font-light leading-none mb-1">{{ $dayNumber }}</p>
                <p class="text-[11px] text-[#C8C0B0]/40 tracking-wide">Jours écrits</p>
            </div>
            <div class="bg-white/[0.03] border border-[#C9973A]/10 rounded-[10px] p-4">
                <p class="font-serif text-[#C9973A] text-3xl font-light leading-none mb-1">{{ 90 - $dayNumber }}</p>
                <p class="text-[11px] text-[#C8C0B0]/40 tracking-wide">Jours restants</p>
            </div>
            <div class="bg-white/[0.03] border border-[#C9973A]/10 rounded-[10px] p-4">
                <p class="font-serif text-[#C9973A] text-3xl font-light leading-none mb-1">Mod. {{ $module }}</p>
                <p class="text-[11px] text-[#C8C0B0]/40 tracking-wide">Module actuel</p>
            </div>
        </div>

        {{-- CTA jour actuel --}}
        <div class="flex items-center gap-4 bg-white/[0.02] border border-[#C9973A]/8 rounded-[10px] p-4 mb-6">
            <p class="font-serif text-[#C9973A] text-4xl font-light leading-none min-w-14">{{ $dayNumber }}</p>
            <div class="flex-1">
                <p class="text-[15px] text-[#E0D5C5] mb-1">Reprendre l'écriture du jour</p>
                <p class="text-[11px] text-[#C8C0B0]/35 tracking-wide">
                    {{ now()->isoFormat('dddd D MMMM YYYY') }} · Module {{ $module }}
                </p>
            </div>
            <a href="{{ route('carnet.day', $dayNumber) }}"
               class="px-4 py-2 bg-[#C9973A]/10 border border-[#C9973A]/20 rounded-lg
                      text-[12px] text-[#C9973A] hover:bg-[#C9973A]/18 whitespace-nowrap transition-all">
                Ouvrir le carnet
            </a>
        </div>

        {{-- Barre progression module --}}
        <div class="flex gap-1.5 mb-2">
            @for($i = 1; $i <= min($dayNumber + 5, 20); $i++)
                <div class="flex-1 h-1.5 rounded-full
                    {{ $i < $dayNumber ? 'bg-[#C9973A]' : ($i === $dayNumber ? 'bg-[#C9973A]/40' : 'bg-white/[0.04]') }}">
                </div>
            @endfor
        </div>
        <p class="text-[11px] text-[#C8C0B0]/30 mb-6">Module {{ $module }} · {{ $progress }}% accompli</p>

        {{-- Dernières entrées --}}
        @if(count($recentEntries))
            <div class="bg-white/[0.02] border border-[#C9973A]/8 rounded-[10px] overflow-hidden">
                <p class="px-4 py-3 text-[11px] tracking-widest uppercase text-[#C9973A]/50 border-b border-[#C9973A]/6">
                    Dernières entrées
                </p>
                @foreach($recentEntries as $entry)
                    <a href="{{ route('carnet.day', $entry->day_number) }}"
                       class="flex items-center gap-3 px-4 py-3 border-b border-white/[0.03] last:border-0
                              hover:bg-[#C9973A]/[0.04] transition-colors">
                        <span class="font-serif text-[#C9973A] text-base font-light min-w-7">{{ $entry->day_number }}</span>
                        <span class="flex-1">
                            <span class="block text-[13px] text-[#C8C0B0]/70">Jour {{ $entry->day_number }}</span>
                            <span class="block text-[11px] text-[#C8C0B0]/30 mt-0.5">
                                {{ $entry->entry_date->isoFormat('D MMMM') }} · Module {{ ceil($entry->day_number / 10) }}
                            </span>
                        </span>
                        <span class="w-2 h-2 rounded-full {{ $entry->is_completed ? 'bg-[#C9973A]' : 'bg-[#C9973A]/30' }}"></span>
                    </a>
                @endforeach
            </div>
        @endif
    @endif
</div>
@endsection
