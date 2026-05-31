<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? config('app.name', 'Laravel Demo') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="auth-body">
    <main class="auth-shell">
        <section class="auth-panel">
            <div class="brand-mark mb-4">
                <span class="brand-icon"><i class="bi bi-shield-lock"></i></span>
                <span>{{ config('app.name', 'Laravel Demo') }}</span>
            </div>

            @include('partials.alerts')

            {{ $slot }}
        </section>
    </main>
</body>
</html>
