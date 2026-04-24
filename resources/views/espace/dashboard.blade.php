{{-- resources/views/espace/dashboard.blade.php --}}
@extends('layouts.espace')
@section('title', 'Tableau de bord')
@section('breadcrumb', 'Tableau de bord')

@section('content')
@include('partials.welcome-video', [
    'videoUrl'   => asset('videos/welcom.mp4'),
    'posterUrl'  => asset('images/carnet-poster.png'),
    'storageKey' => 'rs_welcome_seen_' . auth()->id(),
])
<div class="px-4 sm:px-6 py-6 sm:py-8 max-w-4xl">

   
    <h1 class="font-serif text-[#E8D5A0] text-2xl sm:text-3xl font-light tracking-wide mb-1">
        Bonjour, {{ auth()->user()->prenom ?? auth()->user()->name }}.
    </h1>
    <p class="text-sm text-[#C8C0B0]/40 italic mb-6 sm:mb-8">
        @if($carnet)
            Jour {{ $dayNumber }} de votre traversée · Bienvenue dans votre espace.
        @else
            Bienvenue dans votre espace. Configurez votre carnet pour commencer.
        @endif
    </p>

    @if(!$carnet)
    <div class="bg-[#C9973A]/5 border border-[#C9973A]/20 rounded-xl p-6 sm:p-8 text-center mb-8">
        <p class="font-serif text-[#E8D5A0] text-xl font-light mb-3">Votre carnet n'est pas encore créé</p>
        <p class="text-sm text-[#C8C0B0]/40 mb-6 italic">Commencez votre traversée de 90 jours</p>
        <a href="{{ route('carnet.setup') }}"
           class="inline-block px-8 py-3 bg-[#C9973A] text-[#0A1018] rounded-xl
                  text-[13px] tracking-wide hover:bg-[#E8C47A] transition-all">
            ✦ Configurer mon carnet
        </a>
    </div>
    @else

    @php
        $moduleNames = [1=>'Reset',2=>'Reboot',3=>'Clarté',4=>'Ancrage',5=>'Silence',
                        6=>'Vision',7=>'Lâcher-prise',8=>'Connexion',9=>'Puissance',10=>'Renaissance'];
        $moduleName  = $moduleNames[$module] ?? 'Reset';
        $dayScore    = $dayNumber / 90 * 60;
        $modScore    = ($module - 1) / 10 * 40;
        $waterLevel  = min(100, round($dayScore + $modScore));
        $waterColor  = $waterLevel < 30 ? '#3B8BD4' : ($waterLevel < 70 ? '#1AAAC0' : '#0D8FA0');
    @endphp

    {{-- ── Layout principal ── --}}
    {{-- Desktop : gourde à gauche + contenu à droite --}}
    {{-- Mobile  : gourde en haut centrée + contenu en dessous --}}
    <div class="flex flex-col sm:flex-row gap-6 sm:gap-8 items-start">

        {{-- ── GOURDE ── --}}
        {{-- Mobile : centrée horizontalement en haut --}}
        {{-- Desktop : colonne gauche fixe --}}
        <div class="shrink-0 flex justify-center w-full sm:w-auto sm:pt-5">
            @include('partials.gourde', [
                'day' => $dayNumber,
                'mod' => $module,
            ])
        </div>

        {{-- ── CONTENU PRINCIPAL ── --}}
        <div class="flex-1 min-w-0 w-full">

            {{-- Stats --}}
            <div class="grid grid-cols-3 gap-2 sm:gap-3 mb-4 sm:mb-5">
                <div class="bg-white/[0.03] border border-[#C9973A]/10 rounded-[10px] p-3 sm:p-4">
                    <p class="font-serif text-[#C9973A] text-2xl sm:text-3xl font-light leading-none mb-1">
                        {{ $dayNumber }}
                    </p>
                    <p class="text-[10px] sm:text-[11px] text-[#C8C0B0]/40 tracking-wide">Jours écrits</p>
                </div>
                <div class="bg-white/[0.03] border border-[#C9973A]/10 rounded-[10px] p-3 sm:p-4">
                    <p class="font-serif text-[#C9973A] text-2xl sm:text-3xl font-light leading-none mb-1">
                        {{ 90 - $dayNumber }}
                    </p>
                    <p class="text-[10px] sm:text-[11px] text-[#C8C0B0]/40 tracking-wide">Restants</p>
                </div>
                <div class="bg-white/[0.03] border border-[#C9973A]/10 rounded-[10px] p-3 sm:p-4">
                    <p class="font-serif text-[#C9973A] text-2xl sm:text-3xl font-light leading-none mb-1">
                        {{ $module }}
                    </p>
                    <p class="text-[10px] sm:text-[11px] text-[#C8C0B0]/40 tracking-wide">Module</p>
                </div>
            </div>

            {{-- Barres progression --}}
            <div class="bg-white/[0.02] border border-[#C9973A]/8 rounded-[10px] p-3 sm:p-4 mb-4 sm:mb-5">
                <div class="mb-3">
                    <div class="flex justify-between mb-1.5">
                        <span class="text-[11px] text-[#C8C0B0]/50">Jours complétés</span>
                        <span class="text-[11px] text-[#C9973A] tabular-nums">{{ $dayNumber }}/90</span>
                    </div>
                    <div class="h-[3px] bg-white/5 rounded-full overflow-hidden">
                        <div class="h-full rounded-full bg-[#3B8BD4]"
                             style="width:{{ round($dayNumber/90*100) }}%"></div>
                    </div>
                </div>
                <div class="mb-3">
                    <div class="flex justify-between mb-1.5">
                        <span class="text-[11px] text-[#C8C0B0]/50">Modules terminés</span>
                        <span class="text-[11px] text-[#C9973A] tabular-nums">{{ max(0,$module-1) }}/10</span>
                    </div>
                    <div class="h-[3px] bg-white/5 rounded-full overflow-hidden">
                        <div class="h-full rounded-full bg-[#1AAAC0]"
                             style="width:{{ round(max(0,$module-1)/10*100) }}%"></div>
                    </div>
                </div>
                <div>
                    <div class="flex justify-between mb-1.5">
                        <span class="text-[11px] text-[#C8C0B0]/50">Gourde remplie</span>
                        <span class="text-[11px] font-medium" style="color:{{ $waterColor }}">
                            {{ $waterLevel }}%
                        </span>
                    </div>
                    <div class="h-[3px] bg-white/5 rounded-full overflow-hidden">
                        <div class="h-full rounded-full"
                             style="width:{{ $waterLevel }}%;background:{{ $waterColor }}"></div>
                    </div>
                </div>
            </div>

            {{-- CTA jour --}}
            <div class="flex items-center gap-3 sm:gap-4 bg-white/[0.02] border border-[#C9973A]/8
                        rounded-[10px] p-3 sm:p-4 mb-4 sm:mb-5">
                <p class="font-serif text-[#C9973A] text-3xl sm:text-4xl font-light leading-none min-w-10 sm:min-w-14">
                    {{ $dayNumber }}
                </p>
                <div class="flex-1 min-w-0">
                    <p class="text-[13px] sm:text-[15px] text-[#E0D5C5] mb-0.5 sm:mb-1 truncate">
                        Reprendre l'écriture du jour
                    </p>
                    <p class="text-[10px] sm:text-[11px] text-[#C8C0B0]/35 tracking-wide truncate">
                        {{ now()->isoFormat('dddd D MMMM') }} · {{ $moduleName }}
                    </p>
                </div>
                <a href="{{ route('carnet.day', $dayNumber) }}"
                   class="shrink-0 px-3 sm:px-4 py-2 bg-[#C9973A]/10 border border-[#C9973A]/20 rounded-lg
                          text-[11px] sm:text-[12px] text-[#C9973A] hover:bg-[#C9973A]/18
                          whitespace-nowrap transition-all">
                    Ouvrir →
                </a>
            </div>

            {{-- Dernières entrées --}}
            @if(count($recentEntries))
            <div class="bg-white/[0.02] border border-[#C9973A]/8 rounded-[10px] overflow-hidden">
                <p class="px-4 py-3 text-[10px] sm:text-[11px] tracking-widest uppercase
                           text-[#C9973A]/50 border-b border-[#C9973A]/6">
                    Dernières entrées
                </p>
                @foreach($recentEntries as $entry)
                <a href="{{ route('carnet.day', $entry->day_number) }}"
                   class="flex items-center gap-3 px-4 py-3 border-b border-white/[0.03]
                          last:border-0 hover:bg-[#C9973A]/[0.04] transition-colors">
                    <span class="font-serif text-[#C9973A] text-base font-light min-w-6">
                        {{ $entry->day_number }}
                    </span>
                    <span class="flex-1 min-w-0">
                        <span class="block text-[12px] sm:text-[13px] text-[#C8C0B0]/70 truncate">
                            Jour {{ $entry->day_number }}
                        </span>
                        <span class="block text-[10px] sm:text-[11px] text-[#C8C0B0]/30 mt-0.5 truncate">
                            {{ $entry->entry_date->isoFormat('D MMMM') }}
                            · Module {{ ceil($entry->day_number / 10) }}
                        </span>
                    </span>
                    <span class="w-2 h-2 shrink-0 rounded-full
                        {{ $entry->is_completed ? 'bg-[#C9973A]' : 'bg-[#C9973A]/30' }}">
                    </span>
                </a>
                @endforeach
            </div>
            @endif

        </div>{{-- fin colonne droite --}}
    </div>{{-- fin flex --}}

    @endif
</div>
@endsection