@extends('layouts.admin.app')

@section('title', $title ?? 'Halaman Admin')

@section('content')
<div class="space-y-6">
    <div class="rounded-3xl bg-white p-8 shadow-card">
        <h1 class="text-[28px] font-bold text-slate-900">{{ $title ?? 'Halaman Admin' }}</h1>
        <p class="mt-3 text-sm text-slate-500">
            Halaman ini masih dalam tahap pengembangan.
        </p>
    </div>
</div>
@endsection
