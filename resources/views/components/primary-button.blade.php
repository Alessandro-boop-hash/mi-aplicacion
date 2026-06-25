<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center justify-center rounded-none border border-stone-950 bg-stone-950 px-6 py-3 text-xs font-black uppercase tracking-widest text-white transition hover:bg-white hover:text-stone-950 focus:outline-none focus:ring-1 focus:ring-stone-950 active:bg-stone-900 disabled:opacity-50']) }}>
    {{ $slot }}
</button>
