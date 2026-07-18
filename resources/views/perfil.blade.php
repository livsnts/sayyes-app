@extends('layouts.app')

@section('content')
    <x-navbar />

    <main class="max-w-2xl mx-auto px-6 py-10">

        <h1 class="titulo">Meu Perfil</h1>

        @if (session('sucesso'))
            <div class="mb-6 px-4 py-3 rounded-lg bg-success/10 border border-success text-success text-sm">
                {{ session('sucesso') }}
            </div>
        @endif

        <div class="card-sketch mb-6">
            <div class="flex items-center gap-5 mb-5">
                <div class="w-16 h-16 rounded-full bg-primary flex items-center justify-center text-white text-2xl font-bold shrink-0">
                    {{ strtoupper(substr($usuario->name, 0, 1)) }}
                </div>
                <div>
                    <p class="text-xl font-bold text-primary">{{ $usuario->name }}</p>
                    <p class="text-text-muted text-sm">{{ $usuario->email }}</p>
                </div>
            </div>

            <div class="flex items-center gap-2 text-sm text-text-muted">
                <i class="fa-solid fa-circle-user text-primary"></i>
                <span>Conta do tipo <strong class="text-primary">
                    @if ($usuario->tipoUsuario === 'NOIVO' && $usuario->sexoUsuario === 'F')
                        Noiva
                    @elseif ($usuario->tipoUsuario === 'NOIVO')
                        Noivo
                    @else
                        Assessor
                    @endif
                </strong></span>
            </div>
        </div>

        <div class="card-sketch">
            <h2 class="text-primary font-bold text-lg mb-4">Sobre você</h2>

            <form method="POST" action="{{ route('perfil.update') }}">
                @csrf

                <div class="flex flex-col gap-2">
                    <label for="descricaoUsuario" class="text-primary pt-2 mb-0">
                        Descrição / Bio
                    </label>
                    <textarea
                        name="descricaoUsuario"
                        id="descricaoUsuario"
                        rows="4"
                        placeholder="Conte um pouco sobre você..."
                        class="
                            w-full px-4 py-3
                            rounded-lg
                            border-2 border-primary
                            bg-transparent
                            outline-none resize-none
                            transition-all duration-200
                            focus:ring-2 focus:ring-primary/20
                        "
                    >{{ old('descricaoUsuario', $usuario->descricaoUsuario) }}</textarea>

                    @error('descricaoUsuario')
                        <span class="text-danger text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <x-button type="submit" class="mt-4">
                    Salvar alterações
                </x-button>
            </form>
        </div>

    </main>
@endsection
