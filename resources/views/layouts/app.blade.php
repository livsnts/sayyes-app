<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="icon" href="{{ asset('images/logo-sayyes.png') }}">
    <script src="https://kit.fontawesome.com/6c3e433ed5.js" crossorigin="anonymous"></script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <title>{{ $title ?? 'Say Yes' }}</title>
</head>
<body class="font-primary">
    @yield('content')
</body>
</html>