<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center justify-center rounded-none border border-red-600 bg-red-600 px-6 py-3 text-xs font-black uppercase tracking-widest text-white transition hover:bg-white hover:text-red-600 focus:outline-none focus:ring-1 focus:ring-red-500 active:bg-red-700 disabled:opacity-50']) }}>
    {{ $slot }}
</button>
