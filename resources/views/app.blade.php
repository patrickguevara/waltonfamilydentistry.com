<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        {{-- SEO Meta Tags --}}
        <meta name="author" content="Walton Family Dentistry">
        <meta name="theme-color" content="#000000">

        {{-- Open Graph / Facebook --}}
        <meta property="og:type" content="website">
        <meta property="og:locale" content="en_US">
        <meta property="og:site_name" content="Walton Family Dentistry">

        {{-- Twitter Card --}}
        <meta name="twitter:card" content="summary_large_image">

        {{-- Inline style to set the HTML background color --}}
        <style>
            html {
                background-color: #ffffff;
            }
        </style>

        <title inertia>{{ config('app.name', 'Laravel') }}</title>

        <link rel="icon" href="/favicon.ico" sizes="any">
        <link rel="icon" href="/favicon.svg" type="image/svg+xml">
        <link rel="apple-touch-icon" href="/apple-touch-icon.png">

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

        @vite(['resources/js/app.ts', "resources/js/pages/{$page['component']}.vue"])
        @inertiaHead
    </head>
    <body class="font-sans antialiased">
        @inertia
    </body>
</html>
