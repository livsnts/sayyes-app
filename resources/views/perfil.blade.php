@extends('layouts.app')

@section('content')
    <x-navbar />

    <main class="page-main">

        <h1 class="titulo">Meu Perfil</h1>

        <x-flash-messages />

        <x-card-sketch class="mb-6">
            <div class="flex items-center gap-5">
                <div
                    class="w-16 h-16 rounded-full bg-roxinho flex items-center justify-center text-white text-2xl font-bold shrink-0">
                    <x-tipo-usuario-icone :usuario="$usuario" />
                </div>
                <div>
                    <p class="text-xl font-bold text-primary">{{ $usuario->name }}</p>
                    <p class="text-text-muted text-sm">{{ $usuario->email }}</p>
                </div>
            </div>

        </x-card-sketch>

        <x-card-sketch>
            <div x-data="{ editando: false }">

                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-primary font-bold text-lg">Meus dados</h2>
                    <button @click="editando = !editando" class="text-primary text-sm underline">
                        <span x-show="!editando">Editar</span>
                        <span x-show="editando" x-cloak>Cancelar</span>
                    </button>
                </div>

                {{-- Modo visualização --}}
                <div x-show="!editando" class="flex flex-col gap-3 text-sm">
                    <div>
                        <p class="text-text-muted">Nome</p>
                        <p class="text-primary font-semibold">{{ $usuario->name }}</p>
                    </div>
                    <div>
                        <p class="text-text-muted">E-mail</p>
                        <p class="text-primary font-semibold">{{ $usuario->email }}</p>
                    </div>
                    <div>
                        <p class="text-text-muted">Telefone</p>
                        <p class="text-primary font-semibold">{{ $usuario->telefoneUsuario ?? '—' }}</p>
                    </div>
                    <div>
                        <p class="text-text-muted">Cidade</p>
                        <p class="text-primary font-semibold">{{ $usuario->cidadeUsuario ?? '—' }}</p>
                    </div>
                    <div>
                        <p class="text-text-muted">Bio</p>
                        <p class="text-primary font-semibold">{{ $usuario->descricaoUsuario ?? '—' }}</p>
                    </div>
                </div>

                {{-- Modo edição --}}
                <form x-show="editando" x-cloak method="POST" action="{{ route('perfil.update') }}">
                    @csrf

                    <x-input label="Nome" name="name" :value="$usuario->name" required />
                    <x-input label="E-mail" name="email" type="email" :value="$usuario->email" required />
                    <x-input label="Telefone" name="telefoneUsuario" :value="$usuario->telefoneUsuario" x-data
                        x-mask="(99) 99999-9999" inputmode="numeric" />
                    <x-input label="Cidade" name="cidadeUsuario" :value="$usuario->cidadeUsuario" />
                    <x-textarea label="Bio" name="descricaoUsuario" rows="3" placeholder="Conte um pouco sobre você..."
                        :value="$usuario->descricaoUsuario" />

                    <x-button type="submit" class="mt-4 w-full">
                        Salvar alterações
                    </x-button>
                </form>

            </div>
        </x-card-sketch>

        <div class="flex items-center justify-center mt-6">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <x-button type="submit" variant="danger">
                    Sair da conta
                </x-button>
            </form>
        </div>
    </main>
@endsection