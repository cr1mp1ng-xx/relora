<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Relora COD Market')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    {{-- Paksa isi halaman selebar layar (menimpa batas lebar apa pun di navbar, main, footer) --}}
    <style>
        header > div, main, footer > div { max-width: none !important; }
    </style>
    @stack('styles')
</head>
<body class="min-h-screen flex flex-col text-slate-800">
    @include('partials.navbar')

    <main class="flex-1 w-full px-4 sm:px-7 py-7">
        @yield('content')
    </main>

    @include('partials.footer')

    @stack('scripts')
</body>
</html>