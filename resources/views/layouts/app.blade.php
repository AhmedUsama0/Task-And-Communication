<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{$title}}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Styles / Scripts -->
        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @endif

        @livewireStyles
    </head>
    <body class="grid grid-cols-12 grid-rows-12 min-h-screen overflow-y-hidden max-lg:grid-rows-body-grid-row-mobile">
        @auth
            @livewire('sidebar')
            @livewire('navbar')
            @livewire('task-modal')
            @livewire('chat-manager')
        @endauth
        @livewire('notification')
        {{$slot}}
        @livewireScriptConfig 
    </body>
</html>
