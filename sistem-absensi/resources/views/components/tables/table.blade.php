<div {{ $attributes->merge(['class' => 'overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm']) }}>
    <table class="min-w-full divide-y divide-slate-200 text-sm">
        {{ $slot }}
    </table>
</div>
