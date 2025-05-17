{{-- @props(['active'])

@php
$classes = ($active ?? false)
            ? 'inline-flex items-center px-1 pt-1 border-b-2 border-indigo-400 text-sm font-medium leading-5 text-gray-900 focus:outline-none focus:border-indigo-700 transition duration-150 ease-in-out'
            : 'inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300 transition duration-150 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a> --}}
@props(['href', 'icon' => null, 'active' => false])

<a href="{{ $href }}"
   class="group relative flex items-center px-4 py-2 rounded-full overflow-visible transition-colors duration-300">

    {{-- Fond blanc débordant animé --}}
    <span class="absolute top-0 left-0 h-full w-[120%] z-0 transition-transform duration-300 ease-in-out
        transform origin-left rounded-full
        {{ $active ? 'scale-x-100' : 'scale-x-0 group-hover:scale-x-100' }}
        bg-gray-100">
    </span>

    {{-- Contenu du bouton --}}
    <span class="relative z-10 flex items-center gap-2
        {{ $active ? 'text-[#C19B2C]' : 'text-white group-hover:text-[#C19B2C]' }}">
        @if ($icon)
            <i data-lucide="{{ $icon }}" class="w-4 h-4"></i>
        @endif
        {{ $slot }}
    </span>
</a>



