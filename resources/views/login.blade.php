@extends('layouts.app')

@section('content')
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

                <form method="POST" action="{{ route('login.store') }}">
                    @csrf

                    <x-input label="E-mail" name="email" type="email" />
                    <x-input label="Senha" name="password" type="password" />

                    @error('email')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror

                    <a href="#" class="text-primary text-sm underline mt-1">
                        Esqueci a senha
                    </a>

                    <x-button type="submit" class="py-3 mt-6 w-full">
                        Entrar
                    </x-button>
                </form>

            </x-card-sketch>
        </div>
    </main>
    </div>
@endsection