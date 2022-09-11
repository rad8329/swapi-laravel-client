<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <title>{{ config('app.name') }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite('resources/css/tailwind.min.css')
    @vite('resources/css/app.css')
</head>
    <body class="m-5 bg-white dark:bg-gray-900">
        @yield('content')
        @vite('resources/js/app.js')
    </body>
</html>
