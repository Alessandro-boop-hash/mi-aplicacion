<button {{ $attributes->merge(['type' => 'button', 'class' => 'inline-flex items-center justify-center rounded-md border border-stone-300 bg-white px-4 py-2 text-xs font-bold uppercase text-stone-700 shadow-sm transition hover:border-indigo-200 hover:bg-orange-50/50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-40']) }}>
    {{ $slot }}
</button>
