@extends('layouts.app')

@php dump(session()->all()) @endphp

    @if(session()->has('status'))
        <span class="mb-4 text text-success">
            {{ session()->get('status') }}
        </span>
    @endif

@section('content')
    <x-navbar />

    <main>
        <div class="flex justify-center py-12">
            <x-card-sketch class="w-full max-w-md">
                <h1 class="titulo">Recuperar senha</h1>

                <form method="POST" action="{{ route('password.email') }}">
                    @csrf

                    @error('email')
                        <div class="mb-4 mt-2 px-4 py-3 rounded-lg bg-danger/10 border border-danger text-danger text-sm">
                            {{ $message }}
                        </div>
                    @enderror

                    <x-input label="E-mail" name="email" type="email" />

                  <x-button type="submit" class="py-3 mt-6 w-full">
                        Me envie um link
                    </x-button>
                </form>

            </x-card-sketch>
        </div>
    </main>
    </div>
@endsection