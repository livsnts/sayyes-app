@extends('layouts.app')

@section('content')
    <x-navbar />

    <main>
        <div class="flex justify-center py-12">
            <x-card-sketch class="w-full max-w-md">
                <h1 class="titulo">Login</h1>

                <form method="POST" action="{{ route('login.store') }}">
                    @csrf

                    <x-input label="E-mail" name="email" type="email" />
                    <x-input label="Senha" name="password" type="password" />

                    @error('login')
                        <div class="mb-4 mt-2 px-4 py-3 rounded-lg bg-danger/10 border border-danger text-danger text-sm">
                            {{ $message }}
                        </div>
                    @enderror

                    <a href="{{ route('password.request') }}" class="text-primary text-sm underline mt-1">
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