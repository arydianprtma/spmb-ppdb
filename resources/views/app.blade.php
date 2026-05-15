<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title inertia>{{ config('app.name', 'Portal SPMB - Pesantren Riyadussalikin') }}</title>
    <meta name="description" content="Portal Pendaftaran Santri Baru (SPMB) Pondok Pesantren Riyadussalikin Padaherang. Daftar sekarang dan bergabunglah menjadi bagian dari generasi Qurani.">
    <meta name="keywords" content="spmb pesantren, pendaftaran santri baru, ppdb pesantren, riyadussalikin padaherang">
    <link rel="icon" href="/Logo Riyad.png" type="image/png">

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ config('app.url') }}">
    <meta property="og:title" content="Portal SPMB - Pesantren Riyadussalikin">
    <meta property="og:description" content="Pendaftaran Santri Baru Pondok Pesantren Riyadussalikin Padaherang.">
    <meta property="og:image" content="{{ asset('Logo Riyad.png') }}">

    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="{{ config('app.url') }}">
    <meta property="twitter:title" content="Portal SPMB - Pesantren Riyadussalikin">
    <meta property="twitter:description" content="Pendaftaran Santri Baru Pondok Pesantren Riyadussalikin Padaherang.">
    <meta property="twitter:image" content="{{ asset('Logo Riyad.png') }}">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @routes
    @vite(['resources/js/app.js', "resources/js/Pages/{$page['component']}.vue"])
    @inertiaHead
</head>

<body class="font-sans antialiased">
    @inertia
</body>

</html>