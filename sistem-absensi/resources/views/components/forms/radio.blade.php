<label class="flex items-center gap-3 text-sm text-slate-700">
    <input type="radio" {{ $attributes->merge(['class' => 'h-4 w-4 border-slate-300 text-slate-900 focus:ring-slate-400']) }} />
    <span>{{ $label ?? $slot }}</span>
</label>
