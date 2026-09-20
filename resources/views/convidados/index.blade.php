@extends('layouts.app')

@section('content')

    <main class="page-main-wide">

        <x-flash-messages />

        <div class="page-header">
            <div class="page-header-title-row">
                <img src="{{ asset('images/doodles/listas.png') }}" alt="Convite" class="page-header-doodle">
                <h1 class="titulo">Lista de Convidados</h1>
            </div>
            <p class="text-text-muted">
                Visualize quem estará no <strong class="text-primary">{{ $casamento->nomeCasamento }} </p>
        </div>

        <form method="GET" action="{{ route('convidado.index', $casamento) }}" class="flex gap-3 mb-4">

        <x-input label="Buscar por nome" name="nome" :value="request('nomeConvidado')" placeholder="Ex.: Maria da Silva" />


        </form>
    </main>

@endsection