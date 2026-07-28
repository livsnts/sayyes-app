@extends('layouts.app')

@section('content')
    <x-navbar />

    <main class="page-main">
        <h1 class="titulo">Meus Casamentos</h1>

        {{-- Filtros --}}
        <x-card-sketch class="mb-6">
            <form method="GET" action="{{ route('casamento.index') }}" class="flex flex-col gap-4">

                <x-input label="Buscar por nome" name="nome" :value="request('nome')" placeholder="Ex.: Casamento G&L" />

                <div class="flex gap-4">
                    <div class="flex flex-col gap-2 flex-1">
                        <label class="text-primary pt-2 mb-0">Status</label>
                        <select name="status" class="w-full mt-0 mb-1 px-4 py-4 rounded-lg border-2 border-primary bg-transparent outline-none transition-all duration-200 focus:ring-2 focus:ring-primary/20 focus:border-primary disabled:bg-gray-100 disabled:cursor-not-allowed">
                            <option value="">Todos</option>
                            <option value="ATIVO" {{ request('status') === 'ATIVO' ? 'selected' : '' }}>Ativo</option>
                            <option value="REALIZADO" {{ request('status') === 'REALIZADO' ? 'selected' : '' }}>Realizado
                            </option>
                            <option value="CANCELADO" {{ request('status') === 'CANCELADO' ? 'selected' : '' }}>Cancelado
                            </option>                            
                        </select>
                    </div>

                    <div class="flex gap-4">
                        <x-input label="De" name="data_de" type="date" :value="request('data_de')" class="flex-1" />
                        <x-input label="Até" name="data_ate" type="date" :value="request('data_ate')" class="flex-1" />
                    </div>
                </div>

                <div class="flex gap-3">
                    <x-button type="submit" class="flex-1">Filtrar</x-button>
                    <x-button type="link" href="{{ route('casamento.index') }}"
                        variant="outline" class="flex-1">
                        Limpar
                    </x-button>
                </div>
            </form>
        </x-card-sketch>

        @if ($casamentos->isEmpty())
            <x-card-sketch>
                <p class="text-text-muted text-center">Nenhum casamento encontrado.</p>
            </x-card-sketch>
        @else
            <div class="flex flex-col gap-4">
                @foreach ($casamentos as $casamento)
                    <a href="{{ route('casamento.show', $casamento) }}" class="block hover:opacity-90 transition">
                        <x-card-sketch>
                            <div class="flex items-center gap-4">

                                @if ($casamento->imagemCasamento && file_exists(storage_path('app/public/' . $casamento->imagemCasamento)))
                                    <img src="{{ asset('storage/' . $casamento->imagemCasamento) }}"
                                        class="w-16 h-16 rounded-lg object-cover shrink-0" alt="{{ $casamento->nomeCasamento }}">
                                @else
                                    <div class="w-16 h-16 rounded-lg bg-primary/10 flex items-center justify-center shrink-0">
                                        <i class="fa-solid fa-heart text-primary text-xl"></i>
                                    </div>
                                @endif

                                <div class="flex-1">
                                    <p class="text-primary font-bold text-lg">{{ $casamento->nomeCasamento }}</p>
                                    <p class="text-text-muted text-sm">
                                        {{ $casamento->dataCasamento->format('d/m/Y') }}
                                        @if ($casamento->localCasamento)
                                            · {{ $casamento->localCasamento }}
                                        @endif
                                    </p>
                                </div>

                                <span class="text-xs font-semibold px-3 py-1 rounded-full
                                                @if ($casamento->statusCasamento === 'ATIVO') bg-success/10 text-success
                                                @elseif ($casamento->statusCasamento === 'REALIZADO') bg-primary/10 text-primary
                                                @elseif ($casamento->statusCasamento === 'CANCELADO') bg-danger/10 text-danger
                                                @else bg-warning/10 text-warning
                                                @endif
                                            ">
                                    {{ ucfirst(strtolower($casamento->statusCasamento)) }}
                                </span>

                            </div>
                        </x-card-sketch>
                    </a>
                @endforeach
            </div>
        @endif

        <div class="flex justify-center mt-6">
            <a href="{{ route('casamento.create') }}">
                <x-button><i class="fa fa-plus mr-2" aria-hidden="true"></i> Novo casamento</x-button>
            </a>
        </div>

    </main>
@endsection