{{-- resources/views/livewire/partials/sidebar.blade.php --}}
@php
    $user = auth()->user();
    $carnet = $user->carnet;
    $dayNumber = $carnet ? $carnet->currentDayNumber() : 0;
    $progress  = $carnet ? $carnet->progress_percentage : 0;
    $module    = $carnet ? ceil($dayNumber / 10) : 1;
    $pending   = $carnet ? $carnet->pendingModuleReview() : false;
@endphp

<aside
    x-data="{ collapsed: $persist(false).as('sidebar_collapsed') }"
    :class="collapsed ? 'w-[68px]' : 'w-[260px]'"
    class="relative flex flex-col bg-[#0A1018] border-r border-[#C9973A]/10
           transition-all duration-300 overflow-hidden shrink-0 h-screen"
>
    {{-- ── Logo + Collapse ─────────────────────────────── --}}
    <div class="flex items-center gap-3 px-5 py-6 border-b border-[#C9973A]/10">
        <div class="w-9 h-9 shrink-0 rounded-full bg-[#C9973A]/10 border border-[#C9973A]/30
                    flex items-center justify-center">
            <x-icon.phoenix class="w-5 h-5 text-[#C9973A]" />
        </div>

        <div class="overflow-hidden transition-all duration-300"
             :class="collapsed ? 'w-0 opacity-0' : 'w-full opacity-100'">
            <a href="/" class="font-serif text-[#E8D5A0] text-[15px] tracking-wide whitespace-nowrap">
                Renaît-Sens
            </a>
            <p class="text-[10px] text-[#C9973A]/40 tracking-widest uppercase whitespace-nowrap mt-0.5">
                Traversée · 90 jours
            </p>
        </div>

        <button @click="collapsed = !collapsed"
                class="ml-auto shrink-0 w-7 h-7 rounded-md border border-[#C9973A]/15
                       flex items-center justify-center hover:border-[#C9973A]/40
                       hover:bg-[#C9973A]/8 transition-all duration-200 group">
            <svg class="w-3 h-3 stroke-[#C9973A]/50 fill-none stroke-2
                        group-hover:stroke-[#C9973A] transition-transform duration-300"
                 :class="collapsed ? 'rotate-180' : ''"
                 viewBox="0 0 12 12">
                <path stroke-linecap="round" d="M8 2L4 6L8 10"/>
            </svg>
        </button>
    </div>

    {{-- ── User ────────────────────────────────────────── --}}
    <div class="flex items-center gap-2.5 px-4 py-3 border-b border-white/[0.04]">
        <div class="w-8 h-8 shrink-0 rounded-full bg-gradient-to-br from-[#C9973A] to-[#7B5A20]
                    flex items-center justify-center text-[#0A1018] text-[13px] font-medium">
            {{ strtoupper(substr($user->name, 0, 1)) }}
        </div>
        <div class="overflow-hidden transition-all duration-300"
             :class="collapsed ? 'w-0 opacity-0' : 'w-full opacity-100'">
            <p class="text-[13px] text-[#E0D5C5] whitespace-nowrap truncate">{{ $user->name }}</p>
            <p class="text-[10px] text-[#C9973A] tracking-wider whitespace-nowrap">✦ Membre Premium</p>
        </div>
    </div>

    {{-- ── Progression ─────────────────────────────────── --}}
    <div class="transition-all duration-300 mx-3 mt-3"
         :class="collapsed ? 'opacity-0 h-0 overflow-hidden mb-0' : 'opacity-100 mb-1'">
        <div class="bg-[#C9973A]/5 border border-[#C9973A]/10 rounded-[10px] p-3">
            <p class="text-[9px] tracking-widest uppercase text-[#C9973A]/40 mb-1.5">Progression</p>
            <div class="flex items-baseline gap-1.5 mb-2">
                <span class="font-serif text-[#C9973A] text-2xl font-light leading-none">
                    {{ $dayNumber }}
                </span>
                <span class="text-[11px] text-[#C8C0B0]/30">/90 jours</span>
            </div>
            <div class="h-[3px] bg-white/5 rounded-full overflow-hidden mb-1.5">
                <div class="h-full bg-[#C9973A] rounded-full transition-all duration-700"
                     style="width: {{ $progress }}%"></div>
            </div>
            <p class="text-[11px] text-[#C8C0B0]/40">
                Module {{ $module }} · {{ $progress }}% accompli
            </p>
        </div>
    </div>

    {{-- ── Navigation ──────────────────────────────────── --}}
    <nav class="flex-1 overflow-y-auto overflow-x-hidden scrollbar-none px-3 pt-2">

        {{-- Section Espace --}}
        <x-sidebar.section label="Espace" :collapsed="false" />

        <x-sidebar.item
            route="dashboard"
            label="Tableau de bord"
            sub="Vue d'ensemble"
        >
            <x-slot:icon>
                <path d="M2 2h5v5H2zM9 2h5v5H9zM2 9h5v5H2zM9 9h5v5H9z" rx="1"/>
            </x-slot>
        </x-sidebar.item>

        <x-sidebar.item
            route="espace.carnet.index"
            label="Mon carnet"
            sub="Jour {{ $dayNumber }} · Module {{ $module }}"
            :badge="$dayNumber ?: null"
        >
            <x-slot:icon>
                <path d="M4 2h8a1 1 0 011 1v10a1 1 0 01-1 1H4a1 1 0 01-1-1V3a1 1 0 011-1z"/>
                <path d="M6 6h4M6 9h4M6 12h2"/>
            </x-slot>
        </x-sidebar.item>

        <x-sidebar.item
            route="espace.carnet.bilans"
            label="Bilans"
            sub="{{ $pending ? '1 bilan disponible' : 'À jour' }}"
            :badge="$pending ? 1 : null"
            :badge-alert="$pending"
        >
            <x-slot:icon>
                <circle cx="8" cy="8" r="6"/>
                <path d="M8 5v3l2 2"/>
            </x-slot>
        </x-sidebar.item>

        <x-sidebar.divider />

        {{-- Section Bien-être --}}
        <x-sidebar.section label="Bien-être" :collapsed="false" />

        <x-sidebar.item
            route="espace.detente.index"
            label="Espace détente"
            sub="Méditation · Sons"
        >
            <x-slot:icon>
                <path d="M8 3C4 3 2 6 2 8c0 3 2.5 5 6 5s6-2 6-5c0-2-2-5-6-5z"/>
                <path d="M6 8l1.5 1.5L10 6.5"/>
            </x-slot>
        </x-sidebar.item>

        <x-sidebar.item
            route="espace.detente.musique"
            label="Musique"
            sub="Bibliothèque audio"
        >
            <x-slot:icon>
                <circle cx="5" cy="12" r="2"/>
                <circle cx="12" cy="10" r="2"/>
                <path d="M7 12V5l7-2v7"/>
            </x-slot>
        </x-sidebar.item>

        <x-sidebar.divider />

        {{-- Section Compte --}}
        <x-sidebar.section label="Compte" :collapsed="false" />

        <x-sidebar.item
            route="espace.profile.edit"
            label="Paramètres"
        >
            <x-slot:icon>
                <circle cx="8" cy="8" r="2"/>
                <path d="M8 2v1M8 13v1M2 8h1M13 8h1M3.5 3.5l.7.7M11.8 11.8l.7.7M3.5 12.5l.7-.7M11.8 4.2l.7-.7"/>
            </x-slot>
        </x-sidebar.item>

    </nav>

    {{-- ── Déconnexion ─────────────────────────────────── --}}
    <div class="border-t border-[#C9973A]/8 p-3">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit"
                    class="nav-item w-full flex items-center gap-3 px-2 py-2.5 rounded-lg
                           hover:bg-red-500/5 group transition-colors duration-150">
                <span class="w-9 h-9 shrink-0 rounded-lg bg-white/[0.02] flex items-center justify-center">
                    <svg class="w-4 h-4 fill-none stroke-red-500/40 group-hover:stroke-red-400
                                stroke-[1.5] stroke-linecap-round transition-colors"
                         viewBox="0 0 16 16">
                        <path d="M6 3H3a1 1 0 00-1 1v8a1 1 0 001 1h3M10 11l4-3-4-3M14 8H6"
                              stroke-linejoin="round"/>
                    </svg>
                </span>
                <span class="text-[13px] text-red-500/40 group-hover:text-red-400 whitespace-nowrap
                             transition-all duration-300"
                      :class="collapsed ? 'w-0 opacity-0' : 'w-full opacity-100'">
                    Déconnexion
                </span>
            </button>
        </form>
    </div>
</aside>
