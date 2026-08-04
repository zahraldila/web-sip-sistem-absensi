<button {{ $attributes->merge(['class' => 'inline-flex items-center justify-center rounded-2xl border border-transparent bg-slate-900 px-4 py-3 text-sm font-semibold text-white transition hover:bg-slate-800 focus:outline-none focus:ring-2 focus:ring-slate-400']) }}>
    {{ $slot }}
</button>
