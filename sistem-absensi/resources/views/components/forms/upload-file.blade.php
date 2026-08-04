<div class="space-y-2">
    @if($label ?? false)
        <label class="block text-sm font-semibold text-slate-700">{{ $label }}</label>
    @endif
    <label class="flex cursor-pointer items-center justify-between rounded-3xl border border-dashed border-slate-300 bg-slate-50 px-4 py-4 text-sm text-slate-700 transition hover:border-slate-400 hover:bg-slate-100">
        <span>{{ $placeholder ?? 'Upload file' }}</span>
        <span class="rounded-2xl bg-slate-900 px-3 py-2 text-white">Pilih</span>
        <input type="file" {{ $attributes->merge(['class' => 'hidden']) }} />
    </label>
</div>
