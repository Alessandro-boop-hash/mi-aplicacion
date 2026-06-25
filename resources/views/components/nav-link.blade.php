@props(['active'])

@php
$classes = ($active ?? false)
            ? 'inline-flex items-center px-1 pt-1 border-b-2 border-stone-950 text-xs font-black uppercase tracking-widest leading-5 text-stone-900 focus:outline-none focus:border-stone-950 transition duration-150 ease-in-out'
            : 'inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-xs font-bold uppercase tracking-widest leading-5 text-stone-500 hover:text-stone-950 hover:border-stone-300 focus:outline-none focus:text-stone-950 focus:border-stone-300 transition duration-150 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
