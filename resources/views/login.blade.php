<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('app.name', 'SayYes') }}</title>

    @fonts

    @vite(['resources/css/app.css', 'resources/js/app.js'])

</head>

<body class="font-primary">
    <x-navbar />
    <!-- <div class="decorations">
        <img src="{{ asset('images/doodles/bolo.png') }}" class="decoration cake">
        <img src="{{ asset('images/doodles/aliancas.png') }}" class="decoration ring">
        <img src="{{ asset('images/doodles/buque.png') }}" class="decoration bouquet">
        <img src="{{ asset('images/doodles/carro.png') }}" class="decoration car">
        <img src="{{ asset('images/doodles/tacas.png') }}" class="decoration drinks">
        <img src="{{ asset('images/doodles/mesa.png') }}" class="decoration table">
    </div> -->
    <main>
        <div class="flex justify-center py-12">
            <x-card-sketch class="w-full max-w-md">
                <h1 class="titulo">Login</h1>

                <x-input label="E-mail" name="email" />

                <x-input label="Senha" name="password" type="password" />

                <a href="#" class="
                text-primary
                text-sm
                underline
                mt-1
                ">
                    Esqueci a senha
                </a>

                <x-button class="py-3 mt-6 w-full">
                    Entrar
                </x-button>

            </x-card-sketch>
        </div>
    </main>
    </div>


</body>

</html>