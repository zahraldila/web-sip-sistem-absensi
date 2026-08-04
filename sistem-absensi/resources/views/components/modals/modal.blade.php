<div {{ $attributes->merge(['class' => 'fixed inset-0 z-50 flex items-end justify-center bg-slate-950/40 p-4 sm:items-center']) }}>
    <div class="w-full max-w-xl overflow-hidden rounded-3xl bg-white shadow-2xl">
        {{ $slot }}
    </div>
</div>
