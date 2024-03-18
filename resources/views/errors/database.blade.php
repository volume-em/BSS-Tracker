<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8" />

    <meta name="application-name" content="{{ config('app.name') }}" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />

    <title>{{ config('app.name') }}</title>

    <style>
        html, body {
            width: 100%;
            height: 100%;
        }
    </style>

    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>

    @filamentStyles
    @vite('resources/css/app.css')
</head>

<body class="antialiased w-full h-full">
<div class="flex flex-col items-center justify-center w-full h-full">
    <h1 class="text-3xl">A database error has occurred</h1>

    <div class="mt-5 flex items-center justify-center">
        @if(env('APP_DEBUG'))
            <p class="bg-gray-800 text-white px-4 py-2 rounded-lg text-sm w-1/3 self-center">{{ $exception }}</p>
        @else
            <p class="bg-gray-800 text-white px-4 py-2 rounded-lg text-sm self-center">Set APP_DEBUG=true in .env for more information</p>
        @endif
    </div>
</div>

@filamentScripts
@vite('resources/js/app.js')
</body>
</html>
