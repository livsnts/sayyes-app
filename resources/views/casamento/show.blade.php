@extends('layouts.app')

@section('content')
    <x-navbar />

    <main class="max-w-2xl mx-auto px-6 py-10">
        <h1 class="titulo">{{ $casamento->nomeCasamento }}</h1>

        <x-card-sketch>
             @if ($casamento->imagemCasamento && file_exists(storage_path('app/public/' . $casamento->imagemCasamento)))
                        <img src="{{ asset('storage/' . $casamento->imagemCasamento) }}"
                            class="w-full h-64 overflow-hidden object-cover rounded-lg mb-2">
                    @endif
            <p class="text-text-muted text-sm mb-4">
                {{ $casamento->dataCasamento->format('d/m/Y') }}
                @if ($casamento->localCasamento)
                    · {{ $casamento->localCasamento }}
                @endif
            </p>

            @if ($casamento->descricaoCasamento)
                <p class="mb-4">{{ $casamento->descricaoCasamento }}</p>
            @endif

            @if ($casamento->orcamentoTotal)
                <p class="text-sm">
                    Orçamento: <strong>R$ {{ number_format($casamento->orcamentoTotal, 2, ',', '.') }}</strong>
                </p>
            @endif

            @if ($casamento->urlListaDePresentes)
                <a href="{{ $casamento->urlListaDePresentes }}" target="_blank"
                    class="text-primary text-sm underline mt-2 inline-block">
                    Lista de presentes
                </a>
            @endif

            <a href="{{ route('casamento.edit', $casamento) }}" class="text-primary text-sm underline">
                Editar casamento
            </a>
        </x-card-sketch>
    </main>
@endsection