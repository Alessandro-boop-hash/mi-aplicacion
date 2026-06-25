@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'rounded-none border-stone-300 bg-white shadow-none transition focus:border-stone-950 focus:ring-0 disabled:bg-stone-100 disabled:text-stone-500']) }}>
