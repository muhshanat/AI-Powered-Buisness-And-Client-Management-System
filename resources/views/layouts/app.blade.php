
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'organization Dashboard')</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @if(request()->routeIs('profile.edit'))
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
@endif
</head>
<body class="font-sans antialiased">
    <div class="min-h-screen {{ request()->routeIs('profile.edit') ? 'profile-layout' : 'bg-gray-100' }}">

        @include('layouts.navigation')

        @isset($header)
            <header class="bg-white shadow">
                <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
                    {{ $header }}
                </div>
            </header>
        @endisset

        <main>
            {{ $slot }}
        </main>

    </div>
</body>
</html>
