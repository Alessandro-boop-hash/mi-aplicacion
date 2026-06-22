@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'rounded-md border-stone-300 bg-white shadow-sm transition focus:border-indigo-500 focus:ring-indigo-500 disabled:bg-stone-100 disabled:text-stone-500']) }}>
