@extends('layouts.app')

@section('content')

    <main class="page-main-wide" x-data="{ modalDetalhes: null, modalEditar: null }">

        <div class="page-header">
            <div class="page-header-title-row">
                <img src="{{ asset('images/doodles/listas.png') }}" alt="Convite" class="page-header-doodle">
                <h1 class="titulo">Lista de Convidados</h1>
            </div>
            <p class="text-text-muted">
                Visualize quem estará no <strong class="text-primary">{{ $casamento->nomeCasamento }} </p>
        </div>

        {{-- Cards de resumo: cada um é um link que filtra a tabela pelo status --}}
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
            @php
                $filtros = [
                    null => ['label' => 'Todos', 'valor' => $totalConvidados, 'cor' => 'text-primary'],
                    'CONFIRMADO' => ['label' => 'Confirmados', 'valor' => $confirmados, 'cor' => 'text-success'],
                    'PENDENTE' => ['label' => 'Pendentes', 'valor' => $pendentes, 'cor' => 'text-warning'],
                    'RECUSADO' => ['label' => 'Recusados', 'valor' => $recusados, 'cor' => 'text-danger'],
                ];
            @endphp

            @foreach ($filtros as $valorStatus => $filtro)
                <a href="{{ route('convidado.index', array_filter(['casamento' => $casamento, 'status' => $valorStatus, 'busca' => request('busca')])) }}"
                    class="card-resumo block transition {{ request('status') === $valorStatus ? 'bg-secondary/30' : '' }}">
                    <p class="card-resumo-valor {{ $filtro['cor'] }}">{{ $filtro['valor'] }}</p>
                    <p class="card-resumo-label">{{ $filtro['label'] }}</p>
                </a>
            @endforeach
        </div>

        <form method="GET" action="{{ route('convidado.index', $casamento) }}" class="flex gap-3 mb-4 items-center">
            @if (request('status'))
                <input type="hidden" name="status" value="{{ request('status') }}">
            @endif

            <div class="relative flex-1">
                <input type="text" name="busca" value="{{ request('busca') }}" placeholder="Pesquisar nome"
                    class="font-primary field-input campo-busca">

                @if (request('busca'))
                    <a href="{{ route('convidado.index', array_filter(['casamento' => $casamento, 'status' => request('status')])) }}"
                        class="absolute inset-y-0 right-3 flex items-center text-primary">
                        <i class="fa-solid fa-xmark"></i>
                    </a>
                @endif
            </div>

            <button type="submit" class="botao-detalhes cursor-pointer"><i class="fa-solid fa-magnifying-glass"></i></button>
            <a href="{{ route('convidado.create', $casamento) }}" class="botao-detalhes"><i
                    class="fa-solid fa-plus"></i></a>
        </form>

        <div class="border-2 border-primary rounded-lg overflow-hidden bg-background">
            <table class="w-full text-sm">
                <thead class="bg-primary text-white">
                    <tr>
                        <th class="px-4 py-3 text-left">ID</th>
                        <th class="px-4 py-3 text-left">Status</th>
                        <th class="px-4 py-3 text-left">Nome</th>
                        <th class="px-4 py-3 text-left">Telefone</th>
                        <th class="px-4 py-3 text-center">Acompanhantes</th>
                        <th class="px-4 py-3 text-center">Detalhar</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($convidadosFiltrados as $convidado)
                        <tr class="border-t border-primary/20">
                            <td class="itens-tabela font-semibold">{{ $convidado->id }}</td>
                            <td class="itens-tabela font-semibold"><x-convidado-status :status="$convidado->statusConvidado" />
                            </td>
                            <td class="itens-tabela font-semibold">{{ $convidado->nomeConvidado }}</td>
                            <td class="itens-tabela font-semibold">{{ $convidado->telefoneConvidado ?? '—' }}</td>
                            <td class="itens-tabela text-center font-semibold">{{ $convidado->acompanhantes_count }}</td>
                            <td class="itens-tabela">
                                <div class="flex gap-1 justify-center">
                                    @if ($convidado->statusConvidado === 'PENDENTE' && $convidado->telefoneConvidado)
                                        <a href="{{ $convidado->linkConfirmacaoWhatsapp($casamento->nomeCasamento) }}"
                                            target="_blank" class="icone-acao">
                                            <i class="fa-brands fa-whatsapp"></i>
                                        </a>
                                    @endif
                                    <button type="button" @click="modalDetalhes = {{ $convidado->id }}"
                                        class="icone-acao cursor-pointer">
                                        <i class="fa-regular fa-eye"></i>
                                    </button>
                                    <button type="button" @click="modalEditar = {{ $convidado->id }}"
                                        class="icone-acao cursor-pointer">
                                        <i class="fa-solid fa-pen"></i>
                                    </button>
                                    <form method="POST" action="{{ route('convidado.destroy', [$casamento, $convidado]) }}"
                                        onsubmit="return confirm('Remover {{ $convidado->nomeConvidado }}?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="icone-acao cursor-pointer">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-6 text-center text-text-muted">
                                Nenhum convidado encontrado.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @foreach ($convidadosFiltrados as $convidado)
            <x-convidado-detalhes-modal :casamento="$casamento" :convidado="$convidado" />
            <x-convidado-editar-modal :casamento="$casamento" :convidado="$convidado" />
        @endforeach
    </main>

@endsection