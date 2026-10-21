@extends('layouts.app')

@section('content')

    <main class="page-main-wide">

        <div class="page-header">
            <div class="page-header-title-row">
                <img src="{{ asset('images/doodles/envelope.png') }}" alt="Convite" class="page-header-doodle">
                <h1 class="titulo">Adicionar Convidado</h1>
            </div>
            <p class="text-text-muted">
                Adicione manualmente ou importe uma planilha. Os convidados aparecem na lista ao lado em tempo real.
            </p>
        </div>

        <x-convidado-resumo :estatisticas="$estatisticas" />

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 items-start">
            {{-- Formulário: manual ou importar --}}
            <div class="border-2 border-primary rounded-2xl overflow-hidden">
                <div class="bg-primary text-white px-6 py-3 flex items-center gap-3">
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
                        x-cloak x-data="{ acompanhantes: @js(old('acompanhantes', [])) }">
                        @csrf

                        <x-input label="Nome do convidado*" name="nomeConvidado" required
                            placeholder="Ex.: Maria da Silva" />

                        <x-input label="Telefone / Whatsapp" name="telefoneConvidado" x-data x-mask="(99) 99999-9999"
                            inputmode="numeric" placeholder="(11) 94002-8922" />

                        <div class="grid grid-cols-2 gap-3 mt-4">
                            <div class="flex flex-col gap-2">
                                <label for="faixaEtariaConvidado" class="text-primary">Faixa etária</label>
                                <select id="faixaEtariaConvidado" name="faixaEtariaConvidado" class="field-input">
                                    <option value="">Não informada</option>
                                    @foreach (\App\Enums\FaixaEtaria::cases() as $faixa)
                                        <option value="{{ $faixa->value }}" @selected(old('faixaEtariaConvidado', \App\Enums\FaixaEtaria::Adulto->value) === $faixa->value)>
                                            {{ $faixa->value }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="flex flex-col gap-2">
                                <label for="generoConvidado" class="text-primary">Gênero</label>
                                <select id="generoConvidado" name="generoConvidado" class="field-input">
                                    <option value="">Não informado</option>
                                    @foreach (\App\Enums\Genero::cases() as $genero)
                                        <option value="{{ $genero->value }}" @selected(old('generoConvidado') === $genero->value)>
                                            {{ $genero->value }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="mt-4">
                            <div class="flex items-center justify-between">
                                <label class="text-primary">Acompanhantes</label>
                                <button type="button"
                                    @click="acompanhantes.push({ nome: '', faixaEtaria: @js(\App\Enums\FaixaEtaria::Adulto->value), genero: '' })"
                                    class="text-sm font-semibold text-primary flex items-center gap-1 cursor-pointer">
                                    <i class="fa-solid fa-plus"></i> Adicionar acompanhante
                                </button>
                            </div>

                            <template x-for="(acompanhante, indice) in acompanhantes" :key="indice">
                                <div class="flex gap-2 items-start mt-3 border border-primary/20 rounded-lg p-3">
                                    <div class="flex-1 flex flex-col gap-2">
                                        <input type="text" :name="`acompanhantes[${indice}][nome]`"
                                            x-model="acompanhante.nome" placeholder="Nome do acompanhante"
                                            class="field-input w-full">
                                        <div class="grid grid-cols-2 gap-2">
                                            <select :name="`acompanhantes[${indice}][faixaEtaria]`"
                                                x-model="acompanhante.faixaEtaria" class="field-input">
                                                <option value="">Faixa etária</option>
                                                @foreach (\App\Enums\FaixaEtaria::cases() as $faixa)
                                                    <option value="{{ $faixa->value }}">{{ $faixa->value }}</option>
                                                @endforeach
                                            </select>
                                            <select :name="`acompanhantes[${indice}][genero]`" x-model="acompanhante.genero"
                                                class="field-input">
                                                <option value="">Gênero</option>
                                                @foreach (\App\Enums\Genero::cases() as $genero)
                                                    <option value="{{ $genero->value }}">{{ $genero->value }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <button type="button" @click="acompanhantes.splice(indice, 1)"
                                        class="w-10 h-12 shrink-0 flex items-center justify-center rounded-lg text-primary cursor-pointer">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </div>
                            </template>

                            <p class="text-text-muted text-sm mt-2" x-show="acompanhantes.length === 0">
                                Nenhum acompanhante adicionado.
                            </p>

                            @error('acompanhantes.*.nome')
                                <span class="text-danger text-sm">{{ $message }}</span>
                            @enderror
                        </div>

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
                                <span>Uma linha por convite. Colunas: <strong>nome</strong>, <strong>telefone</strong>,
                                    <strong>faixa_etaria</strong>, <strong>genero</strong> e, até 5 acompanhantes, as
                                    colunas
                                    <strong>acompanhante_1_nome</strong>, <strong>acompanhante_1_faixa_etaria</strong>,
                                    <strong>acompanhante_1_genero</strong> (e assim por diante).
                                    Para a faixa etária, utilize os valores: "4 a 7 anos", "8 a 12 anos", ou "13 anos ou
                                    mais".</span>
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
                    {{-- Cabeçalho fixo no topo --}}
                    <thead class="bg-primary text-white block">
                        <tr class="flex w-full">
                            <th class="itens-tabela text-left w-3/12">Nome</th>
                            <th class="itens-tabela text-left w-2/12">Status</th>
                            <th class="itens-tabela text-left w-3/12">Telefone</th>
                            <th class="itens-tabela text-left w-2/12">Acomp.</th>
                            <th class="itens-tabela text-center w-2/12">Ações</th>
                        </tr>
                    </thead>

                    {{-- Scroll vertical --}}
                    <tbody class="block overflow-y-auto max-h-[40rem] scroll-custom">
                        @forelse ($convidados as $convidado)
                            <tr class="flex w-full items-center border-t border-primary/20">
                                <td class="itens-tabela font-semibold text-primary w-3/12 truncate">
                                    {{ $convidado->nomeConvidado }}
                                </td>
                                <td class="itens-tabela w-2/12">
                                    <x-convidado-status :status="$convidado->statusConvidado" />
                                </td>
                                <td class="itens-tabela text-primary w-4/12">{{ $convidado->telefoneConvidado ?? '—' }}</td>
                                <td class="itens-tabela text-center text-primary w-2/12">{{ $convidado->acompanhantes_count }}
                                </td>
                                <td class="itens-tabela w-2/12">
                                    <div class="flex gap-1 justify-center">
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

    </main>
@endsection