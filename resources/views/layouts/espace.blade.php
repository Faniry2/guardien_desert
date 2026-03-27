{{-- resources/views/layouts/espace.blade.php --}}
<!DOCTYPE html>
<html lang="fr" class="h-full">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>@yield('title', 'Espace Membre') — {{ config('app.name') }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;1,300;1,400&family=Jost:wght@300;400;500&display=swap" rel="stylesheet" />

    <link rel="icon" type="image/png" href="/favicon-96x96.png" sizes="96x96" />
    <link rel="icon" type="image/svg+xml" href="/favicon.svg" />
    <link rel="shortcut icon" href="/favicon.ico" />
    <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png" />
    <link rel="manifest" href="/site.webmanifest" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
    <style>
        * { box-sizing: border-box; }
        
        /* Sidebar labels principaux */
        aside a span.block:first-child { font-size: 15px !important; }
        
        /* Sous-titres sidebar */
        aside a span.block:last-child  { font-size: 12px !important; }
        
        /* Sections ESPACE / BIEN-ÊTRE */
        aside nav p { font-size: 11px !important; letter-spacing: 0.18em; }
        
        /* Nom utilisateur */
        aside .text-\[13px\] { font-size: 15px !important; }
        
        /* Badge Membre Premium */
        aside .text-\[10px\] { font-size: 11px !important; }

        /* Breadcrumb */
        header nav { font-size: 14px !important; }

        /* Tout le contenu principal */
        main { font-size: 15px !important; }
        main p, main span, main label, main input, main textarea, main select {
            font-size: 15px !important;
        }
        main label { font-size: 11px !important; letter-spacing: 0.16em; }
    </style>
    
</head>

<body style="font-size:16px;line-height:1.7" class="h-full bg-[#0E1621] text-[#C8C0B0] antialiased" style="font-family:'Jost',sans-serif">

    <div class="flex h-full" x-data="{ sidebarOpen: $persist(true).as('sidebar_open') }">

        {{-- ── Sidebar ─────────────────────────────── --}}
        @include('partials.sidebar')

        {{-- ── Contenu principal ───────────────────── --}}
        <div class="flex flex-col flex-1 min-w-0 overflow-hidden">

            {{-- Topbar --}}
            <header class="flex items-center justify-between px-6 py-4 border-b border-[#C9973A]/8 bg-[#0A1018]/80 shrink-0">
                <div class="flex items-center gap-3">
                    {{-- Toggle sidebar mobile --}}
                    <button @click="sidebarOpen = !sidebarOpen"
                            class="text-[#C8C0B0]/30 hover:text-[#C9973A] transition-colors lg:hidden">
                        <svg class="w-5 h-5 fill-none stroke-current stroke-[1.5]" viewBox="0 0 20 20">
                            <path stroke-linecap="round" d="M3 5h14M3 10h14M3 15h14"/>
                        </svg>
                    </button>
                    <nav class="text-xs text-[#C8C0B0]/40 tracking-wide">
                        Espace membre
                        @hasSection('breadcrumb')
                            <span class="mx-1.5 text-[#C9973A]/30">·</span>
                            <span class="text-[#C9973A]">@yield('breadcrumb')</span>
                        @endif
                    </nav>
                </div>

                <div class="flex items-center gap-2">
                    @if(auth()->user()->carnet)
                        <a href="{{ route('carnet.day', auth()->user()->carnet->currentDayNumber()) }}"
                           class="px-3.5 py-1.5 text-xs bg-[#C9973A]/8 border border-[#C9973A]/15
                                  rounded-lg text-[#C9973A] tracking-wide hover:bg-[#C9973A]/15 transition-all">
                            Jour {{ auth()->user()->carnet->currentDayNumber() }} →
                        </a>
                    @endif
                </div>
            </header>

            {{-- Contenu --}}
            <main class="flex-1 overflow-y-auto">
                @yield('content')
            </main>
        </div>
    </div>

    {{-- Mini player audio --}}
    @if(auth()->user()->carnet)
        @include('partials.mini-player')
    @endif

    @vite(['resources/js/crypto.js'])
   
    @stack('scripts')
</body>
</html>
