@extends('layouts.app')

@section('content')

    <main>
        <div class="flex justify-center py-12">
            <x-card-sketch class="w-full max-w-md">
                <h1 class="titulo">Recuperar senha</h1>

                <form method="POST" action="{{ route('password.email') }}">
                    @csrf

                    <x-input label="E-mail" name="email" type="email" />

                    <x-button type="submit" class="py-3 mt-6 w-full">
                        Me envie um link
                    </x-button>
                </form>

            </x-card-sketch>
        </div>
    </main>
@endsection