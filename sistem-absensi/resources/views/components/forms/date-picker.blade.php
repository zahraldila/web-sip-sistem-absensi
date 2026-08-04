<div class="space-y-2">
    @if($label ?? false)
        <label class="block text-sm font-semibold text-slate-700">{{ $label }}</label>
    @endif
    <input type="date" {{ $attributes->merge(['class' => 'w-full rounded-3xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-900 focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-200']) }} />
</div>
