{{-- resources/views/partials/sidebar.blade.php --}}
@php
    $user   = auth()->user();
    $carnet = $user->carnet;
    $day    = $carnet ? $carnet->currentDayNumber() : 0;
    $pct    = $carnet ? $carnet->progress_percentage : 0;
    $mod    = $day > 0 ? (int) ceil($day / 10) : 1;
    $route  = request()->route()->getName();
@endphp

<aside x-show="sidebarOpen"
       x-transition:enter="transition-transform duration-300"
       x-transition:enter-start="-translate-x-full"
       x-transition:enter-end="translate-x-0"
       :class="$persist(true).as('sidebar_collapsed') ? 'w-[68px]' : 'w-[260px]'"
       x-data="{ collapsed: $persist(false).as('sidebar_collapsed') }"
       :style="collapsed ? 'width:68px' : 'width:260px'"
       class="flex flex-col bg-[#0A1018] border-r border-[#C9973A]/10
              transition-all duration-300 overflow-hidden shrink-0 h-screen">

    {{-- Logo --}}
        <div class="relative flex items-center gap-2 px-4 py-6 border-b border-[#C9973A]/10">
        {{-- <div class="w-10 h-10 shrink-0 rounded-full bg-[#C9973A]/10 border border-[#C9973A]/30
                    flex items-center justify-center">
            <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="#C9973A" stroke-width="1.2" stroke-linecap="round">
                <path d="M12 3C12 3 18 7 18 12C18 16 15.3 19 12 19C8.7 19 6 16 6 12C6 7 12 3 12 3Z"/>
                <path d="M12 10L12 16M10 12L12 10L14 12"/>
                <path d="M9 19C9 19 7 21 5 21M15 19C15 19 17 21 19 21"/>
            </svg>
        </div> --}}

        <div class="overflow-hidden transition-all duration-300"
            :class="collapsed ? 'opacity-0 w-0' : 'opacity-100 w-full'">
            <p class="font-serif text-[#E8D5A0] text-[18px] tracking-wide whitespace-nowrap">Renaît-Sens</p>
            <p class="text-[16px] text-[#C9973A]/40 tracking-widest uppercase whitespace-nowrap mt-0.5">Traversée · 90 jours</p>
        </div>

        {{-- Chevron — position absolue, toujours visible --}}
        <button @click="collapsed = !collapsed"
                class="absolute right-2 top-1/2 -translate-y-1/2 z-10
                    w-7 h-7 rounded-md border border-[#C9973A]/15
                    flex items-center justify-center
                    hover:border-[#C9973A]/40 hover:bg-[#C9973A]/8 transition-all group">
            <svg class="w-3 h-3 fill-none stroke-[#C9973A]/60 stroke-2
                        group-hover:stroke-[#C9973A] transition-transform duration-300"
                :class="collapsed ? 'rotate-180' : ''"
                viewBox="0 0 12 12">
                <path stroke-linecap="round" d="M8 2L4 6L8 10"/>
            </svg>
        </button>
    </div>

    {{-- User --}}
    <div class="flex items-center gap-2.5 px-4 py-3 border-b border-white/[0.04]">
        <div class="w-10 h-10 shrink-0 rounded-full flex items-center justify-center
                    text-[#0A1018] text-[16px] font-medium"
             style="background: linear-gradient(135deg,#C9973A,#7B5A20)">
            {{ strtoupper(substr($user->prenom ?? $user->name, 0, 1)) }}
        </div>
        <div class="overflow-hidden transition-all duration-300" :class="collapsed ? 'w-0 opacity-0' : 'w-full opacity-100'">
            <p class="text-[16px] text-[#E0D5C5] whitespace-nowrap truncate">{{ $user->prenom ?? $user->name }}</p>
            <p class="text-[16px] text-[#C9973A] tracking-wider whitespace-nowrap">✦ Membre Premium</p>
        </div>
    </div>

    {{-- Progression --}}
    @if($carnet)
        <div class="transition-all duration-300 mx-3 mt-3" :class="collapsed ? 'opacity-0 h-0 overflow-hidden' : 'opacity-100'">
            <div class="bg-[#C9973A]/5 border border-[#C9973A]/10 rounded-[10px] p-3">
                <p class="text-[12px] tracking-widest uppercase text-[#C9973A]/40 mb-1.5">Progression</p>
                <div class="flex items-baseline gap-1.5 mb-2">
                    <span class="font-serif text-[#C9973A] text-3xl font-light leading-none">{{ $day }}</span>
                    <span class="text-[14px] text-[#C8C0B0]/30">/90 jours</span>
                </div>
                <div class="h-[3px] bg-white/5 rounded-full overflow-hidden mb-1.5">
                    <div class="h-full bg-[#C9973A] rounded-full" style="width: {{ $pct }}%"></div>
                </div>
                <p class="text-[14px] text-[#C8C0B0]/40">Module {{ $mod }} · {{ $pct }}% accompli</p>
            </div>
        </div>
    @endif

    {{-- Navigation --}}
    <nav class="flex-1 overflow-y-auto px-3 pt-3 space-y-0.5" style="scrollbar-width:none">

        {{-- Section Espace --}}
        <p class="text-[12px] tracking-[0.14em] uppercase text-[#C9973A]/35 px-2 pt-3 pb-2 transition-all"
           :class="collapsed ? 'opacity-0 h-0 overflow-hidden py-0' : 'opacity-100'">Espace</p>

        @php
        $navItems = [
            ['route' => 'espace.dashboard', 'label' => 'Tableau de bord', 'sub' => 'Vue d\'ensemble', 'icon' => '<rect x="2" y="2" width="5" height="5" rx="1"/><rect x="9" y="2" width="5" height="5" rx="1"/><rect x="2" y="9" width="5" height="5" rx="1"/><rect x="9" y="9" width="5" height="5" rx="1"/>'],
            ['route' => 'carnet.index',     'label' => 'Mon carnet',      'sub' => 'Jour '.$day.' · Module '.$mod, 'badge' => $day ?: null, 'icon' => '<path d="M4 2h8a1 1 0 011 1v10a1 1 0 01-1 1H4a1 1 0 01-1-1V3a1 1 0 011-1z"/><path d="M6 6h4M6 9h4M6 12h2"/>'],
            ['route' => 'carnet.bilans',    'label' => 'Bilans',          'sub' => 'Bilans de module', 'icon' => '<circle cx="8" cy="8" r="6"/><path d="M8 5v3l2 2"/>'],
        ];
        @endphp

        @foreach($navItems as $item)
            @php $active = str_starts_with($route ?? '', $item['route']) @endphp
            <a href="{{ route($item['route']) }}"
               class="relative flex items-center gap-3 px-2 py-2.5 rounded-lg transition-colors duration-150 group
                      {{ $active ? 'bg-[#C9973A]/12' : 'hover:bg-[#C9973A]/7' }}">
                @if($active)
                    <span class="absolute left-0 top-1/2 -translate-y-1/2 w-0.5 h-5 bg-[#C9973A] rounded-r-sm"></span>
                @endif
                <span class="w-10 h-10 shrink-0 rounded-lg flex items-center justify-center
                             {{ $active ? 'bg-[#C9973A]/15' : 'bg-white/[0.03]' }}">
                    <svg class="w-5 h-5 fill-none stroke-[1.5] stroke-linecap-round stroke-linejoin-round transition-colors
                                {{ $active ? 'stroke-[#C9973A]' : 'stroke-[#968C78]/70 group-hover:stroke-[#C9973A]' }}"
                         viewBox="0 0 16 16">{!! $item['icon'] !!}</svg>
                </span>
                <span class="overflow-hidden transition-all duration-300 min-w-0"
                      :class="collapsed ? 'w-0 opacity-0' : 'w-full opacity-100'">
                    <span class="block text-[16px] whitespace-nowrap transition-colors
                                 {{ $active ? 'text-[#E8D5A0]' : 'text-[#C8C0B0]/70 group-hover:text-[#E8D5A0]' }}">
                        {{ $item['label'] }}
                    </span>
                    @if(isset($item['sub']))
                        <span class="block text-[16px] text-[#C9973A]/40 whitespace-nowrap mt-0.5">{{ $item['sub'] }}</span>
                    @endif
                </span>
                @if(isset($item['badge']) && $item['badge'])
                    <span class="ml-auto shrink-0 min-w-[18px] h-[18px] rounded-full bg-[#C9973A]/20
                                 text-[#C9973A] text-[16px] flex items-center justify-center px-1.5"
                          :class="collapsed ? 'hidden' : 'flex'">{{ $item['badge'] }}</span>
                @endif
            </a>
        @endforeach

        <div class="h-px bg-white/[0.04] my-2 mx-1"></div>

        {{-- Section Bien-être --}}
        <p class="text-[12px] tracking-[0.14em] uppercase text-[#C9973A]/35 px-2 pt-2 pb-2 transition-all"
           :class="collapsed ? 'opacity-0 h-0 overflow-hidden py-0' : 'opacity-100'">Bien-être</p>

        @php
        $detenteItems = [
            ['route' => 'detente.index',   'label' => 'Espace détente', 'sub' => 'Méditation · Sons',  'icon' => '<path d="M8 3C4 3 2 6 2 8c0 3 2.5 5 6 5s6-2 6-5c0-2-2-5-6-5z"/><path d="M6 8l1.5 1.5L10 6.5"/>'],
            ['route' => 'detente.musique', 'label' => 'Musique',        'sub' => 'Bibliothèque audio', 'icon' => '<circle cx="5" cy="12" r="2"/><circle cx="12" cy="10" r="2"/><path d="M7 12V5l7-2v7"/>'],
        ];
        @endphp

        @foreach($detenteItems as $item)
            @php $active = str_starts_with($route ?? '', $item['route']) @endphp
            <a href="{{ route($item['route']) }}"
               class="relative flex items-center gap-3 px-2 py-2.5 rounded-lg transition-colors duration-150 group
                      {{ $active ? 'bg-[#C9973A]/12' : 'hover:bg-[#C9973A]/7' }}">
                @if($active)
                    <span class="absolute left-0 top-1/2 -translate-y-1/2 w-0.5 h-5 bg-[#C9973A] rounded-r-sm"></span>
                @endif
                <span class="w-10 h-10 shrink-0 rounded-lg flex items-center justify-center
                             {{ $active ? 'bg-[#C9973A]/15' : 'bg-white/[0.03]' }}">
                    <svg class="w-5 h-5 fill-none stroke-[1.5] stroke-linecap-round stroke-linejoin-round transition-colors
                                {{ $active ? 'stroke-[#C9973A]' : 'stroke-[#968C78]/70 group-hover:stroke-[#C9973A]' }}"
                         viewBox="0 0 16 16">{!! $item['icon'] !!}</svg>
                </span>
                <span class="overflow-hidden transition-all duration-300 min-w-0"
                      :class="collapsed ? 'w-0 opacity-0' : 'w-full opacity-100'">
                    <span class="block text-[16px] whitespace-nowrap transition-colors
                                 {{ $active ? 'text-[#E8D5A0]' : 'text-[#C8C0B0]/70 group-hover:text-[#E8D5A0]' }}">
                        {{ $item['label'] }}
                    </span>
                    <span class="block text-[16px] text-[#C9973A]/40 whitespace-nowrap mt-0.5">{{ $item['sub'] }}</span>
                </span>
            </a>
        @endforeach

        <div class="h-px bg-white/[0.04] my-2 mx-1"></div>

        {{-- Paramètres --}}
        <a href="{{ route('profile.edit') }}"
           class="flex items-center gap-3 px-2 py-2.5 rounded-lg hover:bg-[#C9973A]/7 transition-colors group">
            <span class="w-10 h-10 shrink-0 rounded-lg bg-white/[0.03] flex items-center justify-center">
                <svg class="w-5 h-5 fill-none stroke-[#968C78]/70 stroke-[1.5] group-hover:stroke-[#C9973A] transition-colors" viewBox="0 0 16 16">
                    <circle cx="8" cy="8" r="2"/>
                    <path stroke-linecap="round" d="M8 2v1M8 13v1M2 8h1M13 8h1M3.5 3.5l.7.7M11.8 11.8l.7.7M3.5 12.5l.7-.7M11.8 4.2l.7-.7"/>
                </svg>
            </span>
            <span class="overflow-hidden transition-all duration-300" :class="collapsed ? 'w-0 opacity-0' : 'w-full opacity-100'">
                <span class="block text-[16px] text-[#C8C0B0]/70 group-hover:text-[#E8D5A0] whitespace-nowrap transition-colors">Paramètres</span>
            </span>
        </a>
    </nav>

    {{-- Déconnexion --}}
    <div class="border-t border-[#C9973A]/8 p-3">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit"
                    class="w-full flex items-center gap-3 px-2 py-2.5 rounded-lg hover:bg-red-500/5 group transition-colors">
                <span class="w-10 h-10 shrink-0 rounded-lg bg-white/[0.02] flex items-center justify-center">
                    <svg class="w-5 h-5 fill-none stroke-red-500/40 group-hover:stroke-red-400 stroke-[1.5]" viewBox="0 0 16 16">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 3H3a1 1 0 00-1 1v8a1 1 0 001 1h3M10 11l4-3-4-3M14 8H6"/>
                    </svg>
                </span>
                <span class="text-[16px] text-red-500/40 group-hover:text-red-400 whitespace-nowrap transition-all"
                      :class="collapsed ? 'w-0 opacity-0' : 'w-full opacity-100'">
                    Déconnexion
                </span>
            </button>
        </form>
    </div>
</aside>
