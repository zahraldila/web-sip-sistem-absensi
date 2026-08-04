<?php

if (! function_exists('supabase_url')) {
    function supabase_url(): ?string
    {
        return env('SUPABASE_URL');
    }
}

if (! function_exists('supabase_key')) {
    function supabase_key(): ?string
    {
        return env('SUPABASE_KEY');
    }
}

if (! function_exists('upload_path')) {
    function upload_path(string $path = ''): string
    {
        return storage_path('app/uploads/' . ltrim($path, '/'));
    }
}
