<button {{ $attributes->merge(['type' => 'button', 'class' => 'inline-flex items-center justify-center rounded-none border-2 border-stone-300 bg-white px-6 py-3 text-xs font-black uppercase tracking-widest text-stone-900 transition hover:border-stone-950 focus:outline-none focus:ring-1 focus:ring-stone-950 disabled:opacity-40']) }}>
    {{ $slot }}
</button>
