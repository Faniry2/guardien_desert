{{-- resources/views/components/sidebar/item.blade.php --}}
@props([
    'route',
    'label',
    'sub'         => null,
    'badge'       => null,
    'badgeAlert'  => false,
    'icon'        => null,
])

@php
    $active = request()->routeIs($route) || request()->routeIs($route.'.*');
@endphp

<a href="{{ route($route) }}"
   class="relative flex items-center gap-3 px-2 py-2.5 rounded-lg mb-0.5
          transition-colors duration-150 group
          {{ $active
              ? 'bg-[#C9973A]/12'
              : 'hover:bg-[#C9973A]/7' }}">

    {{-- Active indicator --}}
    @if ($active)
        <span class="absolute left-0 top-1/2 -translate-y-1/2 w-0.5 h-5
                     bg-[#C9973A] rounded-r-sm"></span>
    @endif

    {{-- Icon --}}
    <span class="w-9 h-9 shrink-0 rounded-lg flex items-center justify-center transition-colors
                 {{ $active ? 'bg-[#C9973A]/15' : 'bg-white/[0.03]' }}">
        <svg class="w-4 h-4 fill-none stroke-[1.5] stroke-linecap-round stroke-linejoin-round
                    transition-colors duration-150
                    {{ $active
                        ? 'stroke-[#C9973A]'
                        : 'stroke-[#968C78]/70 group-hover:stroke-[#C9973A]' }}"
             viewBox="0 0 16 16">
            {!! $icon !!}
        </svg>
    </span>

    {{-- Text --}}
    <span class="overflow-hidden transition-all duration-300 min-w-0"
          :class="collapsed ? 'w-0 opacity-0' : 'w-full opacity-100'">
        <span class="block text-[13px] whitespace-nowrap transition-colors duration-150
                     {{ $active
                         ? 'text-[#E8D5A0]'
                         : 'text-[#C8C0B0]/70 group-hover:text-[#E8D5A0]' }}">
            {{ $label }}
        </span>
        @if ($sub)
            <span class="block text-[10px] text-[#C9973A]/40 whitespace-nowrap mt-0.5">
                {{ $sub }}
            </span>
        @endif
    </span>

    {{-- Badge --}}
    @if ($badge)
        <span class="ml-auto shrink-0 min-w-[18px] h-[18px] rounded-full text-[10px]
                     font-medium flex items-center justify-center px-1.5 transition-all duration-300
                     {{ $badgeAlert
                         ? 'bg-amber-500/20 text-amber-400'
                         : 'bg-[#C9973A]/20 text-[#C9973A]' }}"
              :class="collapsed ? 'hidden' : 'flex'">
            {{ $badge }}
        </span>
    @endif
</a>
