<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="icon" href="{{ asset('images/logo-sayyes.png') }}">
    <script src="https://kit.fontawesome.com/6c3e433ed5.js" crossorigin="anonymous"></script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <title>@yield('codigo') - @yield('titulo') | Say Yes</title>
</head>

<body class="font-primary min-h-screen flex items-center justify-center px-4">
    <div class="card-sketch max-w-lg w-full text-center">
        <i class="fa-solid @yield('icone') text-5xl text-primary mb-4"></i>

        <p class="text-7xl font-bold text-primary leading-none">@yield('codigo')</p>
        <h1 class="titulo mt-2">@yield('titulo')</h1>

        <p class="text-text-muted mb-6">@yield('mensagem')</p>

        <div class="flex flex-wrap justify-center gap-3">
            <a href="{{ url('/') }}"
               class="px-8 py-2 rounded-lg border-2 border-primary bg-primary text-white hover:bg-primary-dark transition-colors duration-200">
                Ir para o início
            </a>

            <a href="javascript:history.back()"
               class="px-8 py-2 rounded-lg border-2 border-primary text-primary hover:bg-primary hover:text-white transition-colors duration-200">
                Voltar
            </a>
        </div>
    </div>
</body>

</html>