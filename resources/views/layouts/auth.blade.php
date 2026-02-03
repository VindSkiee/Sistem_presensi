<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('app.name', 'Laravel') }} - Auth</title>

    <link rel="icon" type="image/png" href="{{ asset('favicon.ico') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @stack('styles')
</head>

<body class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50/30 to-slate-50">

    {{-- AUTH CONTENT --}}
    @yield('content')

    @stack('scripts')
</body>
</html>
