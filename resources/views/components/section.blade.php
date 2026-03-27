{{-- resources/views/components/sidebar/section.blade.php --}}
@props(['label'])

<p class="text-[9px] tracking-[0.14em] uppercase text-[#C9973A]/35
          px-2 pt-4 pb-2 whitespace-nowrap overflow-hidden transition-all duration-300"
   :class="collapsed ? 'opacity-0 h-0 py-0' : 'opacity-100'">
    {{ $label }}
</p>
