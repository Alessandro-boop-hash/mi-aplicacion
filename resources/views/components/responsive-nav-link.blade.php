@props(['active'])

@php
$classes = ($active ?? false)
            ? 'block w-full ps-3 pe-4 py-2 border-l-4 border-indigo-500 text-start text-base font-bold text-indigo-800 bg-orange-50/70 focus:outline-none focus:text-indigo-900 focus:bg-orange-100 focus:border-indigo-700 transition duration-150 ease-in-out'
            : 'block w-full ps-3 pe-4 py-2 border-l-4 border-transparent text-start text-base font-semibold text-stone-600 hover:text-stone-900 hover:bg-stone-50 hover:border-stone-300 focus:outline-none focus:text-stone-900 focus:bg-stone-50 focus:border-stone-300 transition duration-150 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
