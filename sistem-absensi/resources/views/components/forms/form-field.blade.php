<div class="space-y-2">
    @if($label ?? false)
        <label class="block text-sm font-semibold text-slate-700">{{ $label }}</label>
    @endif
    {{ $slot }}
</div>
