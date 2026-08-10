<?php

if (! function_exists('supabase_url')) {
    function supabase_url(): ?string
    {
        $url = env('SUPABASE_URL');
        if (! $url) {
            return null;
        }

        $url = trim($url);
        if ($url === '') {
            return null;
        }

        if (! preg_match('/^https?:\/\//i', $url)) {
            $url = 'https://' . ltrim($url, '/');
        }

        return rtrim($url, '/');
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

if (! function_exists('getInitials')) {
    function getInitials(?string $name): string
    {
        $name = trim($name ?? '');
        if ($name === '') {
            return '';
        }

        $parts = preg_split('/\s+/', $name);
        if (count($parts) === 1) {
            return strtoupper(substr($parts[0], 0, 2));
        }

        $first = strtoupper(substr($parts[0], 0, 1));
        $last = strtoupper(substr($parts[count($parts) - 1], 0, 1));

        return $first . $last;
    }
}

if (! function_exists('supabase_public_url')) {
    function supabase_public_url(?string $path): ?string
    {
        $path = trim($path ?? '');
        if ($path === '') {
            return null;
        }

        $baseUrl = config('supabase.url') ?: env('SUPABASE_URL');
        if (empty(trim($baseUrl ?? ''))) {
            return null;
        }

        $bucket = config('supabase.bucket', 'profile-images');
        $bucket = trim($bucket ?: 'profile-images');
        $path = ltrim($path, '/');

        return rtrim($baseUrl, '/') . '/storage/v1/object/public/' . rawurlencode($bucket) . '/' . implode('/', array_map('rawurlencode', explode('/', $path)));
    }
}
