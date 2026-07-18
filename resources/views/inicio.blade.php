@extends('layouts.app')

@section('content')

<x-navbar />

<section
    class="
        max-w-7xl
        mx-auto
        px-8
        py-12
    "
>

    <div
        class="
            flex
            flex-col
            lg:flex-row
            items-center
            justify-center
            gap-20
        "
    >

        {{-- Bolo --}}
        <div class="floating-cake">
            <img
                src="{{ asset('images/homepage-noivos.png') }}"
                alt="Casamento"
                class="w-94"
            >
        </div>

        {{-- Texto --}}
        <div class="max-w-xl text-center lg:text-left">

            <h1
                class="
                    font-primary
                    text-primary
                    text-4xl
                    lg:text-6xl
                    font-bold
                    leading-tight
                "
            >
                Menos planilhas,<br>
                Mais memórias.
            </h1>

            <p
                class="
                    mt-6
                    text-primary
                    text-lg
                "
            >
                Organize convidados, fornecedores
                e finanças do seu casamento
                em um só lugar.
            </p>

        </div>

    </div>

@endsection