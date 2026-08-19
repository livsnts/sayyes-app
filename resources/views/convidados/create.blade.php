@extends('layouts.app')

@section('content')
    <x-navbar />

    <main class="page-main-wide">

        <x-flash-messages />

        <div class="page-header">
            <div class="page-header-title-row">
                <img src="{{ asset('images/doodles/convite.png') }}" alt="Convite" class="page-header-doodle">
                <h1 class="titulo">Adicionar Convidado</h1>
            </div>
            <p class="text-text-muted">
                Adicione manualmente ou importe uma planilha. Os convidados aparecem na lista ao lado em tempo real.
            </p>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
            <div class="card-resumo">
                <p class="text-3xl font-bold text-primary">{{ $totalConvidados }}</p>
                <p class="card-resumo-label">Cadastrados</p>
            </div>
            <div class="card-resumo">
                <p class="text-3xl font-bold text-success">{{ $confirmados }}</p>
                <p class="card-resumo-label">Confirmados</p>
            </div>
            <div class="card-resumo">
                <p class="text-3xl font-bold text-warning">{{ $pendentes }}</p>
                <p class="card-resumo-label">Pendentes</p>
            </div>
            <div class="card-resumo">
                <p class="text-3xl font-bold text-danger">{{ $recusados }}</p>
                <p class="card-resumo-label">Recusados</p>
            </div>
        </div>

        {{-- Painel de cadastro --}}
        <div class="flex flex-col lg:flex-row gap-8 items-start">
            
        </div>

    </main>
@endsection