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

        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-4">
            <div class="card-resumo">
                <p class="card-resumo-valor text-primary">{{ $totalConvidados }}</p>
                <p class="card-resumo-label">Cadastrados</p>
            </div>
            <div class="card-resumo">
                <p class="card-resumo-valor text-success">{{ $totalPessoasConfirmadas }}</p>
                <p class="card-resumo-label">Confirmados</p>
            </div>
            <div class="card-resumo">
                <p class="card-resumo-valor text-warning">{{ $pendentes }}</p>
                <p class="card-resumo-label">Pendentes</p>
            </div>
            <div class="card-resumo">
                <p class="card-resumo-valor text-danger">{{ $recusados }}</p>
                <p class="card-resumo-label">Recusados</p>
            </div>
        </div>

        <div class="card-resumo flex items-center justify-center gap-3 mb-8">
            <p class="card-resumo-valor text-primary">{{ $capacidadeMaxima }}</p>
            <div class="text-left">
                <p class="card-resumo-label">Capacidade máxima</p>
                <p class="text-text-muted text-xs">Convidados + acompanhantes cadastrados</p>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 items-start">

            {{-- Formulário: manual ou importar --}}
            <div class="border-2 border-primary rounded-2xl overflow-hidden">
                <div class="bg-primary text-white px-6 py-4 flex items-center gap-3">
                    <i class="fa-solid fa-plus"></i>
                    <h2 class="font-bold text-lg">Adicionar Convidado</h2>
                </div>

                <div class="p-6" x-data="{ aba: '{{ $errors->has('planilha') ? 'importar' : 'manual' }}' }">

                    {{-- Abas --}}
                    <div class="flex gap-2 mb-6">
                        <button type="button" @click="aba = 'manual'"
                            :class="aba === 'manual' ? 'bg-secondary/40 border-primary' : 'border-primary/30'"
                            class="flex-1 border-2 rounded-full py-2 font-semibold text-primary transition">
                            Manual
                        </button>
                        <button type="button" @click="aba = 'importar'"
                            :class="aba === 'importar' ? 'bg-secondary/40 border-primary' : 'border-primary/30'"
                            class="flex-1 border-2 rounded-full py-2 font-semibold text-primary transition">
                            Importar Planilha
                        </button>
                    </div>

                    {{-- Manual --}}
                    <form method="POST" action="{{ route('convidado.store', $casamento) }}" x-show="aba === 'manual'"
                        x-cloak x-data="{ qtd: {{ old('quantidadeMaxAcompanhantes', 1) }} }">
                        @csrf

                        <x-input label="Nome do convidado*" name="nomeConvidado" required
                            placeholder="Ex.: Maria da Silva" />

                        <x-input label="Telefone / Whatsapp" name="telefoneConvidado" x-data x-mask="(99) 99999-9999"
                            inputmode="numeric" placeholder="(11) 94002-8922" />

                        <div class="flex flex-col gap-2 mt-4">
                            <label class="text-primary">Quant. máx. de acompanhantes</label>
                            <div class="flex items-center gap-3">
                                <button type="button" @click="qtd = Math.max(0, qtd - 1)"
                                    class="w-10 h-10 rounded-lg border-2 border-primary text-primary font-bold cursor-pointer">
                                    &minus;
                                </button>
                                <span class="w-8 text-center font-bold text-lg text-primary" x-text="qtd"></span>
                                <button type="button" @click="qtd++"
                                    class="w-10 h-10 rounded-lg border-2 border-primary text-primary font-bold cursor-pointer">
                                    +
                                </button>
                                <input type="hidden" name="quantidadeMaxAcompanhantes" :value="qtd">
                                <span class="text-text-muted text-sm">adultos</span>
                            </div>
                            @error('quantidadeMaxAcompanhantes')
                                <span class="text-danger text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <x-textarea label="Alergias" name="alergiasConvidado" placeholder="Ex.: Alergia a frutos do mar" />

                        <x-button type="submit" class="w-full mt-6">Salvar</x-button>
                    </form>

                    {{-- Importar planilha --}}
                    <div x-show="aba === 'importar'" x-cloak>
                        <form method="POST" action="{{ route('convidado.importar', $casamento) }}"
                            enctype="multipart/form-data" x-data="{ arquivo: null, arrastando: false }">
                            @csrf

                            <div
                                class="flex items-center justify-between gap-3 px-4 py-3 rounded-lg border border-primary/20 text-text-muted text-sm mb-4">
                                <span>Baixe o modelo de planilha e preencha com os dados dos convidados antes de
                                    importar.</span>
                                <a href="{{ route('convidado.modelo', $casamento) }}"
                                    class="shrink-0 flex items-center gap-2 px-4 py-2 rounded-lg bg-primary text-white font-semibold whitespace-nowrap">
                                    <i class="fa-solid fa-download"></i> Modelo
                                </a>
                            </div>

                            <div
                                class="flex gap-3 items-start px-4 py-3 rounded-lg border border-primary/20 text-text-muted text-sm mb-4">
                                <i class="fa-solid fa-circle-info text-primary mt-0.5"></i>
                                <span>Colunas esperadas na planilha: <strong>nome</strong>, <strong>telefone</strong>,
                                    <strong>acompanhantes</strong>.</span>
                            </div>

                            <label @dragover.prevent="arrastando = true" @dragleave.prevent="arrastando = false"
                                @drop.prevent="arrastando = false; arquivo = $event.dataTransfer.files[0]; $refs.planilha.files = $event.dataTransfer.files"
                                :class="arrastando ? 'bg-secondary/10 border-primary' : 'border-primary/40'"
                                class="flex flex-col items-center justify-center gap-2 border-2 border-dashed rounded-xl py-10 cursor-pointer text-center transition">
                                <i class="fa-solid fa-paperclip text-2xl text-primary"></i>
                                <span class="font-bold text-primary" x-show="!arquivo">Arraste a planilha aqui</span>
                                <span class="font-bold text-primary" x-show="arquivo" x-text="arquivo?.name" x-cloak></span>
                                <span class="text-text-muted text-sm">ou clique para selecionar<br>.xlsx, .xls ou
                                    .csv</span>
                                <input type="file" name="planilha" x-ref="planilha" accept=".xlsx,.xls,.csv" class="hidden"
                                    @change="arquivo = $event.target.files[0]">
                            </label>
                            @error('planilha')
                                <span class="text-danger text-sm">{{ $message }}</span>
                            @enderror

                            <x-button type="submit" class="w-full mt-6">Importar</x-button>
                        </form>
                    </div>

                </div>
            </div>

            <div class="border-2 border-primary rounded-2xl overflow-hidden bg-background">
                <table class="w-full text-sm block">
                    {{-- Cabeçalho fixo no topo, fora do scroll --}}
                    <thead class="bg-primary text-white block">
                        <tr class="flex w-full">
                            <th class="px-4 py-3 text-left w-3/12">Nome</th>
                            <th class="px-4 py-3 text-left w-2/12">Status</th>
                            <th class="px-4 py-3 text-left w-3/12">Telefone</th>
                            <th class="px-4 py-3 text-left w-2/12">Acomp.</th>
                            <th class="px-4 py-3 text-left w-2/12">Ações</th>
                        </tr>
                    </thead>

                    {{-- Scroll vertical aplicado EXCLUSIVAMENTE ao tbody --}}
                    <tbody class="block overflow-y-auto max-h-[25rem] scroll-custom">
                        @forelse ($convidados as $convidado)
                            <tr class="flex w-full items-center border-t border-primary/20">
                                <td class="px-4 py-3 font-semibold text-primary w-3/12 truncate">{{ $convidado->nomeConvidado }}
                                </td>
                                <td class="px-4 py-3 w-2/12">
                                    <span class="font-semibold
                                    @if ($convidado->statusConvidado === 'CONFIRMADO') text-success
                                    @elseif ($convidado->statusConvidado === 'RECUSADO') text-danger
                                    @else text-warning
                                    @endif
                                ">
                                        {{ ucfirst(strtolower($convidado->statusConvidado)) }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-primary w-3/12">{{ $convidado->telefoneConvidado ?? '—' }}</td>
                                <td class="px-4 py-3 text-primary w-2/12">
                                    {{ $convidado->acompanhantes_count }}/{{ $convidado->quantidadeMaxAcompanhantes }}
                                </td>
                                <td class="px-4 py-3 w-2/12">
                                    <div class="flex gap-2">
                                        @if ($convidado->statusConvidado === 'PENDENTE' && $convidado->telefoneConvidado)
                                            <a href="{{ $convidado->linkConfirmacaoWhatsapp($casamento->nomeCasamento) }}"
                                                target="_blank"
                                                class="w-9 h-9 flex items-center justify-center rounded-lg bg-primary text-white shrink-0">
                                                <i class="fa-brands fa-whatsapp"></i>
                                            </a>
                                        @endif
                                        <form method="POST" action="{{ route('convidado.destroy', [$casamento, $convidado]) }}"
                                            onsubmit="return confirm('Remover {{ $convidado->nomeConvidado }}?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="w-9 h-9 flex items-center justify-center rounded-lg bg-primary text-white cursor-pointer">
                                                <i class="fa-solid fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr class="flex w-full">
                                <td class="w-full px-4 py-6 text-center text-text-muted">
                                    Nenhum convidado cadastrado ainda.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </div>

    </main>

@endsection