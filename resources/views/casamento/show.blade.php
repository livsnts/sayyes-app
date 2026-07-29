@extends('layouts.app')

@section('content')
    <x-navbar />

    <main class="page-main">

        {{-- Polaroid --}}
        <div class="flex justify-center mb-8">
            <div class="bg-primary-light p-4 pb-8 rotate-[-1.5deg] max-w-xs w-full border-2 border-primary"
                style="box-shadow: 6px 6px 0 var(--color-primary)">

                @if ($casamento->imagemCasamento && file_exists(storage_path('app/public/' . $casamento->imagemCasamento)))
                    <img src="{{ asset('storage/' . $casamento->imagemCasamento) }}"
                        class="w-full h-56 object-cover border-2 border-primary" alt="{{ $casamento->nomeCasamento }}">
                @else
                    <div class="w-full h-56 bg-primary/10 flex items-center justify-center">
                        <i class="fa-solid fa-heart text-primary text-5xl"></i>
                    </div>
                @endif

                <div class="mt-4 text-center">
                    <p class="font-bold text-primary text-lg" style="font-family: var(--font-primary)">
                        {{ $casamento->nomeCasamento }}
                    </p>
                    <p class="text-text-muted text-xs mt-1">
                        {{ $casamento->dataCasamento->format('d/m/Y') }}
                        @if ($casamento->localCasamento)
                            · {{ $casamento->localCasamento }}
                        @endif
                    </p>
                    <span class="text-xs font-semibold px-3 py-1 rounded-full mt-2 inline-block
                                                @if ($casamento->statusCasamento === 'ATIVO') bg-success/10 text-success
                                                @elseif ($casamento->statusCasamento === 'REALIZADO') bg-primary/10 text-primary
                                                @elseif ($casamento->statusCasamento === 'CANCELADO') bg-danger/10 text-danger
                                                @else bg-warning/10 text-warning
                                                @endif">
                        {{ ucfirst(strtolower($casamento->statusCasamento)) }}
                    </span>
                </div>

            </div>
        </div>

        {{-- Info rápida --}}
        <x-card-sketch class="mb-6">
            <div class="grid grid-cols-3 divide-x divide-primary/20">
                <div class="flex flex-col items-center gap-1 py-2 px-2 text-center">
                    <i class="fa-regular fa-calendar text-primary"></i>
                    <p class="text-xs text-text-muted">Data</p>
                    <p class="text-sm font-semibold text-primary">{{ $casamento->dataCasamento->format('d/m/Y') }}</p>
                </div>
                <div class="flex flex-col items-center gap-1 py-2 px-2 text-center">
                    <i class="fa-solid fa-location-dot text-primary"></i>
                    <p class="text-xs text-text-muted">Local</p>
                    <p class="text-sm font-semibold text-primary">{{ $casamento->localCasamento ?? '—' }}</p>
                </div>
                <div class="flex flex-col items-center gap-1 py-2 px-2 text-center">
                    <i class="fa-solid fa-wallet text-primary"></i>
                    <p class="text-xs text-text-muted">Orçamento</p>
                    <p class="text-sm font-semibold text-primary">
                        @if ($casamento->orcamentoTotal)
                            R$ {{ number_format($casamento->orcamentoTotal, 2, ',', '.') }}
                        @else
                            —
                        @endif
                    </p>
                </div>
            </div>
            <div class="flex flex-col justify-center mt-6">
                <p class="text-sm font-semibold text-primary">Descrição do evento:</p>
                @if ($casamento->descricaoCasamento)
                    <p class="text-sm text-text mt-1">{{ $casamento->descricaoCasamento }}</p>
                @else
                    <p class="text-sm text-text-muted mt-1">—</p>
                @endif
            </div>

        </x-card-sketch>

        {{-- Descrição --}}


        {{-- Ações rápidas --}}
        <div class="grid grid-cols-3 gap-4 mb-6">
            <a href="{{ route('casamento.equipe', $casamento) }}">
                <div class="card-acao flex flex-col items-center gap-2 hover:opacity-70 transition cursor-pointer">
                    <img src="{{ asset('images/doodles/passaros.png') }}" alt="Passaros" class="h-24 object-contain">
                    <p class="text-primary font-semibold text-sm">Equipe do evento</p>
                </div>
            </a>
            <a href="{{ route('casamento.edit', $casamento) }}">
                <div class="card-acao flex flex-col items-center gap-2 hover:opacity-70 transition cursor-pointer">
                    <img src="{{ asset('images/doodles/caneta.png') }}" alt="Caneta" class="h-24 object-contain">
                    <p class="text-primary font-semibold text-sm">Editar casamento</p>
                </div>
            </a>
            <a href="{{ route('casamento.edit', $casamento) }}">
                <div class="card-acao flex flex-col items-center gap-2 hover:opacity-70 transition cursor-pointer">
                    <img src="{{ asset('images/doodles/caneta.png') }}" alt="Caneta" class="h-24 object-contain">
                    <p class="text-primary font-semibold text-sm">Editar casamento</p>
                </div>
            </a>
        </div>

        {{-- Lista de presentes --}}
        @if ($casamento->urlListaDePresentes)
            <div class="text-center">
                <a href="{{ $casamento->urlListaDePresentes }}" target="_blank"
                    class="text-primary text-sm underline opacity-70 hover:opacity-100 transition">
                    <i class="fa-solid fa-gift mr-1"></i> Ver lista de presentes
                </a>
            </div>
        @endif

    </main>
@endsection