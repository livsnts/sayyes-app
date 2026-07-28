@extends('layouts.app')

@section('content')
    <x-navbar />

    <main class="page-main">

        <p class="text-sm text-text-muted mb-6">
            <a href="{{ route('casamento.show', $casamento) }}" class="text-muted hover:text-primary">Meu casamento</a>
            &rsaquo; Equipe do evento
        </p>

        <div class="relative flex items-center justify-center mb-8">
            <img src="{{ asset('images/doodles/passaros.png') }}" alt="Passaros" class="absolute left-0 w-30">
            <div class="text-center">
                <h1 class="titulo mb-0">Equipe do evento</h1>
                <p class="text-text-muted">
                    Gerencie quem tem acesso ao <strong class="text-primary">{{ $casamento->nomeCasamento }}</strong>
                </p>
            </div>
        </div>

        <x-flash-messages />

        {{-- Membros atuais --}}
        <x-card-sketch class="mb-6">
            <h2 class="text-primary font-bold text-lg mb-4">Membros atuais</h2>

            <div class="flex flex-col gap-3">
                @foreach ($membros as $membro)
                    <div class="flex items-center gap-4 border-2 border-primary/20 rounded-lg px-4 py-3">
                        <x-tipo-usuario-icone :usuario="$membro" />
                        <div>
                            <p class="font-semibold text-primary">{{ $membro->name }}</p>
                            <p class="text-text-muted text-sm">
                                {{ $membro->telefoneUsuario }}
                                &middot;
                                {{ $membro->email }}
                            </p>
                        </div>
                    </div>
                @endforeach
            </div>
        </x-card-sketch>

        {{-- Adicionar membro --}}
        <div class="card-sketch" x-data="{
            email: '',
            resultado: null,
            jaMembro: false,
            naoEncontrado: false,
            carregando: false,
            async buscar() {
                this.resultado = null;
                this.jaMembro = false;
                this.naoEncontrado = false;
                this.carregando = true;
                const res = await fetch('{{ route('casamento.buscar-usuario', $casamento) }}?email=' + encodeURIComponent(this.email));
                const data = await res.json();
                this.carregando = false;
                if (data.ja_membro) {
                    this.jaMembro = true;
                } else if (data.encontrado) {
                    this.resultado = data;
                } else {
                    this.naoEncontrado = true;
                }
            }
        }">
            <h2 class="text-primary font-bold text-lg mb-4">Adicionar membro</h2>

            <form @submit.prevent="buscar" class="flex gap-3 mb-4">
                <input type="email" x-model="email" placeholder="E-mail cadastrado no SayYes"
                    class="field-input flex-1">
                <x-button type="submit" class="px-6 py-3">
                    <span x-show="!carregando">Buscar</span>
                    <span x-show="carregando">Buscando</span>
                </x-button>
            </form>

            {{-- Encontrado --}}
            <div x-show="resultado && resultado.encontrado" x-cloak
                class="alert-success flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-primary flex items-center justify-center text-white font-bold shrink-0"
                    x-text="resultado ? resultado.name[0].toUpperCase() : ''"></div>
                <div>
                    <p class="font-semibold text-primary" x-text="resultado ? resultado.name : ''"></p>
                    <p class="text-text-muted text-sm" x-text="resultado ? resultado.tipo : ''"></p>
                </div>
            </div>

            {{-- Não encontrado --}}
            <div x-show="naoEncontrado" x-cloak class="alert-danger">
                Nenhuma conta encontrada com este e-mail.
            </div>

            {{-- Já é membro --}}
            <div x-show="jaMembro" x-cloak class="alert-warning">
                Este usuário já é membro do casamento.
            </div>

            <div class="mb-4 flex gap-3 items-start px-4 py-3 rounded-lg border border-primary/20 text-text-muted text-sm">
                <i class="fa-solid fa-circle-info text-primary mt-0.5"></i>
                <span>A pessoa precisa ter uma conta no SayYes para ser vinculada.</span>
            </div>

            <form x-show="resultado && resultado.encontrado" x-cloak method="POST"
                action="{{ route('casamento.adicionar-membro', $casamento) }}">
                @csrf
                <input type="hidden" name="user_id" x-bind:value="resultado ? resultado.id : ''">
                <x-button type="submit" class="w-full">
                    Adicionar ao casamento
                </x-button>
            </form>
        </div>

    </main>
@endsection
