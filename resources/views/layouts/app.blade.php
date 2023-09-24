<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">

    <meta name="application-name" content="{{ config('app.name') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('app.name') }}</title>

    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>
    @vite(['resources/css/app.css','resources/css/filament-fullcalendar.css', 'resources/css/apexcharts.css', 'resources/js/app.js', 'resources/js/filament-fullcalendar.js', 'resources/js/apexcharts.min.js'])
    @filamentStyles
    @filamentScripts
    @stack('scripts')
</head>

<body class="antialiased">
    {{ $slot }}
    @livewire('notifications')
</body>

</html>