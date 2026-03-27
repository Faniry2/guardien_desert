{{-- resources/views/layouts/app.blade.php --}}
<!DOCTYPE html>
<html lang="fr" class="h-full">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>{{ config('app.name', 'Renaît-Sens') }} — {{ $title ?? 'Espace Membre' }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;1,300;1,400&family=Jost:wght@300;400;500&display=swap"
          rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>

<body class="h-full bg-[#0E1621] text-[#C8C0B0] font-['Jost',sans-serif] antialiased">

    {{-- Lock overlay (géré par Livewire) --}}
    {{-- @livewire('carnet.lock-overlay') --}}

    {{-- Mini-player audio persistant --}}
    {{-- Mini-player audio persistant --}}
    @if(auth()->user()->carnet)
        @livewire('detente.mini-player')
    @endif

    <div class="flex h-full">

        {{-- ── Sidebar ──────────────────────────────── --}}
        @include('livewire.partials.sidebar')

        {{-- ── Contenu principal ───────────────────── --}}
        <div class="flex flex-col flex-1 min-w-0 overflow-hidden">

            {{-- Top bar --}}
            <header class="flex items-center justify-between px-6 py-4
                           border-b border-[#C9973A]/8 bg-[#0A1018]/80
                           backdrop-blur-sm shrink-0">
                <nav class="text-xs text-[#C8C0B0]/40 tracking-wide">
                    Espace membre
                    @if (isset($breadcrumb))
                        <span class="mx-1.5 text-[#C9973A]/30">·</span>
                        <span class="text-[#C9973A]">{{ $breadcrumb }}</span>
                    @endif
                </nav>

                <div class="flex items-center gap-2">
                    {{-- Verrouiller le carnet --}}
                    @if(auth()->user()->carnet)
                        @livewire('carnet.lock-button')
                    @endif

                    {{-- Aller au jour actuel --}}
                    @if(auth()->user()->carnet)
                        <a href="{{ route('carnet.day', auth()->user()->carnet->currentDayNumber()) }}"
                        class="px-3.5 py-1.5 text-xs bg-[#C9973A]/8 border border-[#C9973A]/15
                                rounded-lg text-[#C9973A] tracking-wide hover:bg-[#C9973A]/15
                                hover:border-[#C9973A]/35 transition-all">
                            Jour {{ auth()->user()->carnet->currentDayNumber() }} →
                        </a>
                    @endif
                </div>
            </header>

            {{-- Page content --}}
            <main class="flex-1 overflow-y-auto scrollbar-thin scrollbar-track-transparent
                         scrollbar-thumb-[#C9973A]/20 hover:scrollbar-thumb-[#C9973A]/40">
                {{ $slot }}
            </main>
        </div>
    </div>

    @livewireScripts
    <script src="{{ asset('js/crypto.js') }}"></script>
    @stack('scripts')
</body>
</html>
