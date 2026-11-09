@extends('layouts.app')

@section('content')

    @php
        $moeda = fn($valor) => 'R$ ' . number_format($valor, 2, ',', '.');
        $coresStatus = [
            'EM_PESQUISA' => 'bg-primary/10 text-primary',
            'EM_NEGOCIACAO' => 'bg-warning/10 text-warning',
            'CONTRATADO' => 'bg-success/10 text-success',
            'CANCELADO' => 'bg-danger/10 text-danger',
        ];
        // reabre o modal de cadastro se a validação falhou nele (os de edição usam PUT)
        $abrirCadastro = $errors->any() && old('_method') !== 'PUT' && old('totalParcelas') === null;
    @endphp

    <main class="page-main-wide"
        x-data="{ modalEditar: null, modalCadastro: {{ $abrirCadastro ? 'true' : 'false' }}, modalParcelas: null }">

        <x-breadcrumb-casamento />

        <div class="page-header">
            <div class="page-header-title-row">
                <img src="{{ asset('images/doodles/brinde.png') }}" alt="Brinde" class="w-24">
                <h1 class="titulo">Fornecedores</h1>
                <button type="button" @click="modalCadastro = true" class="botao-detalhes">
                    <i class="fa-solid fa-plus"></i>
                </button>
            </div>
            <p class="text-text-muted pt-2">
                Gerencie os fornecedores do casamento e acompanhe quanto já foi contratado.
            </p>
        </div>

        @if ($errors->any())
            <div class="mb-4 text-danger text-sm">
                @foreach ($errors->all() as $erro)
                    <p>{{ $erro }}</p>
                @endforeach
            </div>
        @endif

        {{-- Resumo financeiro --}}
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 mb-2">
            <div class="border-2 border-primary rounded-2xl p-4">
                <p class="text-xs text-text-muted">Orçamento total</p>
                <p class="text-xl font-bold text-primary">{{ $moeda($casamento->orcamentoTotal) }}</p>
            </div>
            <div class="border-2 border-primary rounded-2xl p-4">
                <p class="text-xs text-text-muted">Contratado</p>
                <p class="text-xl font-bold text-primary">{{ $moeda($totalContratado) }}</p>
            </div>
            <div class="border-2 border-primary rounded-2xl p-4">
                <p class="text-xs text-text-muted">Disponível</p>
                <p class="text-xl font-bold {{ $disponivel < 0 ? 'text-danger' : 'text-success' }}">
                    {{ $moeda($disponivel) }}
                </p>
            </div>
        </div>

        {{-- Filtro por categoria --}}
        <form method="GET" action="{{ route('fornecedor-casamento.index', $casamento) }}"
            class="flex items-center gap-2 mb-4">
            <label for="categoria" class="text-primary text-sm">Filtrar por categoria:</label>
            <select id="categoria" name="categoria"
                class="w-58 px-4 py-3 rounded-lg border-2 border-primary bg-transparent outline-none"
                onchange="this.form.submit()">
                <option value="">Todas as categorias</option>
                @foreach (\App\Enums\CategoriaFornecedor::cases() as $categoria)
                    <option value="{{ $categoria->value }}" @selected(request('categoria') === $categoria->value)>
                        {{ $categoria->value }}
                    </option>
                @endforeach
            </select>

            @if (request('categoria'))
                <a href="{{ route('fornecedor-casamento.index', $casamento) }}" class="text-primary text-sm underline">Limpar
                    filtro</a>
            @endif
        </form>

        {{-- Tabela de fornecedores --}}
        <div class="border-2 border-primary rounded-2xl overflow-hidden bg-background">
            <table class="w-full text-sm">
                <thead class="bg-primary text-white">
                    <tr>
                        <th class="itens-tabela text-left">Fornecedor</th>
                        <th class="itens-tabela text-left">Categoria</th>
                        <th class="itens-tabela text-left">Status</th>
                        <th class="itens-tabela text-left">Telefone</th>
                        <th class="itens-tabela text-left">Valor</th>
                        <th class="itens-tabela text-left">Total</th>
                        <th class="itens-tabela text-left">Obs.</th>
                        <th class="itens-tabela text-center">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($fornecedores as $fornecedor)
                        <tr class="border-t border-primary/20">
                            <td class="itens-tabela font-semibold text-primary">
                                {{ $fornecedor->nomeFornecedorCasamento }}
                                @if ($fornecedor->fornecedor_confianca_id)
                                    <i class="fa-solid fa-star text-xs" title="Fornecedor de confiança do assessor"></i>
                                @endif
                            </td>
                            <td class="itens-tabela text-primary">{{ $fornecedor->categoriaFornecedorCasamento?->value ?? '—' }}
                            </td>
                            <td class="itens-tabela">
                                <span
                                    class="text-xs font-semibold px-3 py-1 rounded-full {{ $coresStatus[$fornecedor->statusFornecedorCasamento] }}">
                                    {{ \App\Models\FornecedorCasamento::STATUS[$fornecedor->statusFornecedorCasamento] }}
                                </span>
                            </td>
                            <td class="itens-tabela text-primary">{{ $fornecedor->telefoneFornecedorCasamento ?? '—' }}</td>
                            <td class="itens-tabela text-primary">
                                @if ($fornecedor->tipoValorFornecedorCasamento === \App\Enums\TipoValorFornecedor::Fixo)
                                    <span class="text-xs text-text-muted">Valor fixo</span>
                                @else
                                    {{ $moeda($fornecedor->valorAdultoFornecedorCasamento) }} <span
                                        class="text-xs text-text-muted">/ adulto
                                        (×{{ $fornecedor->quantidadeAdultosFornecedorCasamento }})</span><br>
                                    {{ $moeda($fornecedor->valorCriancaFornecedorCasamento) }} <span
                                        class="text-xs text-text-muted">/ criança
                                        (×{{ $fornecedor->quantidadeCriancasFornecedorCasamento }})</span>
                                @endif
                            </td>
                            <td class="itens-tabela font-semibold text-primary">
                                {{ $moeda($fornecedor->valorTotalFornecedorCasamento) }}
                            </td>
                            <td class="itens-tabela text-text-muted">{{ $fornecedor->observacoesFornecedorCasamento ?? '—' }}
                            </td>
                            <td class="itens-tabela">
                                <div class="flex gap-1 justify-center">
                                    @if ($fornecedor->statusFornecedorCasamento === 'CONTRATADO')
                                        <button type="button" @click="modalParcelas = {{ $fornecedor->id }}"
                                            class="icone-acao cursor-pointer" title="Parcelas">
                                            <i class="fa-solid fa-dollar-sign fa-lg"></i>
                                        </button>
                                    @endif
                                    <button type="button" @click="modalEditar = {{ $fornecedor->id }}"
                                        class="icone-acao cursor-pointer" title="Editar">
                                        <i class="fa-solid fa-pen"></i>
                                    </button>
                                    <form method="POST"
                                        action="{{ route('fornecedor-casamento.destroy', [$casamento, $fornecedor]) }}"
                                        onsubmit="return confirm('Remover {{ $fornecedor->nomeFornecedorCasamento }}?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="icone-acao cursor-pointer" title="Remover">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-4 py-6 text-center text-text-muted">
                                Nenhum fornecedor cadastrado ainda.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Modal de cadastro --}}
        <x-modal show="modalCadastro" onClose="modalCadastro = false">
            <h2 class="titulo text-center mb-4">Adicionar fornecedor</h2>

            <form method="POST" action="{{ route('fornecedor-casamento.store', $casamento) }}" x-data="{
                            nome: @js(old('nomeFornecedorCasamento', '')),
                            categoria: @js(old('categoriaFornecedorCasamento', '')),
                            telefone: @js(old('telefoneFornecedorCasamento', '')),
                            tipo: @js(old('tipoValorFornecedorCasamento', 'FIXO')),
                            confiancaId: '',
                            adulto: '',
                            crianca: '',
                            qtdAdultos: '',
                            qtdCriancas: '',
                            total: '',
                            paraNumero(valor) {
                                return parseFloat((valor || '').replace(/[^\d,]/g, '').replace(',', '.')) || 0;
                            },
                            calcularTotal() {
                                if (this.tipo !== 'POR_PESSOA') { return; }
                                const soma = this.paraNumero(this.adulto) * (parseInt(this.qtdAdultos) || 0)
                                    + this.paraNumero(this.crianca) * (parseInt(this.qtdCriancas) || 0);
                                this.total = 'R$ ' + soma.toLocaleString('pt-BR', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
                            }
                        }"
                x-init="['adulto', 'crianca', 'qtdAdultos', 'qtdCriancas'].forEach(campo => $watch(campo, () => calcularTotal()))">
                @csrf
                <input type="hidden" name="fornecedor_confianca_id" x-model="confiancaId">

                @if ($sugestoes->isNotEmpty())
                    {{-- puxa nome e categoria de um fornecedor de confiança do assessor --}}
                    <div class="flex flex-col gap-2 mb-2">
                        <label class="text-primary">Puxar do assessor</label>
                        <select class="field-input"
                            @change="const op = $event.target.selectedOptions[0]; nome = op.dataset.nome || nome; categoria = op.dataset.categoria || categoria; telefone = op.dataset.telefone || telefone; confiancaId = $event.target.value">
                            <option value="">Selecione um fornecedor de confiança…</option>
                            @foreach ($sugestoes as $sugestao)
                                <option value="{{ $sugestao->id }}" data-nome="{{ $sugestao->nomeFornecedorConfianca }}"
                                    data-categoria="{{ $sugestao->categoriaFornecedorConfianca?->value }}"
                                    data-telefone="{{ $sugestao->telefoneFornecedorConfianca }}">
                                    {{ $sugestao->nomeFornecedorConfianca }}
                                    @if ($sugestao->categoriaFornecedorConfianca)
                                        — {{ $sugestao->categoriaFornecedorConfianca->value }}
                                    @endif
                                </option>
                            @endforeach
                        </select>
                    </div>
                @endif

                <div class="flex flex-col gap-2 mt-4">
                    <label for="nomeFornecedorCasamento" class="text-primary">Nome*</label>
                    <input type="text" id="nomeFornecedorCasamento" name="nomeFornecedorCasamento" x-model="nome"
                        @input="confiancaId = ''" required placeholder="Ex.: Buffet do João" class="field-input">
                </div>

                <div class="flex flex-col gap-2 mt-4">
                    <label for="categoriaFornecedorCasamento" class="text-primary">Categoria*</label>
                    <select id="categoriaFornecedorCasamento" name="categoriaFornecedorCasamento" x-model="categoria"
                        required class="field-input">
                        <option value="">Selecione</option>
                        @foreach (\App\Enums\CategoriaFornecedor::cases() as $categoria)
                            <option value="{{ $categoria->value }}">{{ $categoria->value }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="flex flex-col gap-2 mt-4">
                    <label for="telefoneFornecedorCasamento" class="text-primary">Telefone</label>
                    <input type="text" id="telefoneFornecedorCasamento" name="telefoneFornecedorCasamento"
                        x-model="telefone" x-mask="(99) 99999-9999" inputmode="numeric" placeholder="(11) 94002-8922"
                        class="field-input">
                </div>

                <div class="flex flex-col gap-2 mt-4">
                    <label for="statusFornecedorCasamento" class="text-primary">Status*</label>
                    <select id="statusFornecedorCasamento" name="statusFornecedorCasamento" class="field-input">
                        @foreach (\App\Models\FornecedorCasamento::STATUS as $chave => $texto)
                            <option value="{{ $chave }}" @selected(old('statusFornecedorCasamento', 'EM_PESQUISA') === $chave)>
                                {{ $texto }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="flex flex-col gap-2 mt-4">
                    <label for="tipoValorFornecedorCasamento" class="text-primary">Tipo de valor*</label>
                    <select id="tipoValorFornecedorCasamento" name="tipoValorFornecedorCasamento" x-model="tipo"
                        class="field-input">
                        @foreach (\App\Enums\TipoValorFornecedor::cases() as $tipo)
                            <option value="{{ $tipo->value }}">{{ $tipo->rotulo() }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- valores unitários e quantidades: só no tipo "por pessoa" --}}
                <div x-show="tipo === 'POR_PESSOA'" x-cloak>
                    <p class="text-xs text-text-muted mt-4">
                        Lembrete: você possui {{ $pessoas['adultos'] }} convidados adultos e {{ $pessoas['criancas'] }}
                        crianças no seu evento (bebês e recusados não entram na contagem).
                    </p>

                    <div class="flex flex-col gap-2 mt-4">
                        <label for="valorAdulto" class="text-primary">Valor por adulto*</label>
                        <input type="text" id="valorAdulto" name="valorAdulto" inputmode="numeric" x-model="adulto"
                            :disabled="tipo !== 'POR_PESSOA'" x-mask:dynamic="'R$ ' + $money($input, ',', '.', 2)"
                            class="field-input">
                    </div>
                    <div class="flex flex-col gap-2 mt-4">
                        <label for="valorCrianca" class="text-primary">Valor por criança*</label>
                        <input type="text" id="valorCrianca" name="valorCrianca" inputmode="numeric" x-model="crianca"
                            :disabled="tipo !== 'POR_PESSOA'" x-mask:dynamic="'R$ ' + $money($input, ',', '.', 2)"
                            class="field-input">
                    </div>
                    <div class="flex flex-col gap-2 mt-4">
                        <label for="quantidadeAdultos" class="text-primary">Quantidade de adultos*</label>
                        <input type="number" min="0" step="1" id="quantidadeAdultos" name="quantidadeAdultos"
                            x-model="qtdAdultos" :disabled="tipo !== 'POR_PESSOA'" class="field-input">
                    </div>
                    <div class="flex flex-col gap-2 mt-4">
                        <label for="quantidadeCriancas" class="text-primary">Quantidade de crianças*</label>
                        <input type="number" min="0" step="1" id="quantidadeCriancas" name="quantidadeCriancas"
                            x-model="qtdCriancas" :disabled="tipo !== 'POR_PESSOA'" class="field-input">
                    </div>
                </div>

                {{-- total: sempre visível e editável --}}
                <div class="flex flex-col gap-2 mt-4">
                    <label for="valorTotal" class="text-primary">Valor total*</label>
                    <input type="text" id="valorTotal" name="valorTotal" inputmode="numeric" x-model="total"
                        x-mask:dynamic="'R$ ' + $money($input, ',', '.', 2)" class="field-input">
                    <span class="text-xs text-text-muted" x-show="tipo === 'POR_PESSOA'" x-cloak>
                        Calculado como (valor por adulto × quantidade de adultos) + (valor por criança × quantidade de
                        crianças). Você pode editar o valor.
                    </span>
                </div>

                <x-textarea label="Observações" name="observacoesFornecedorCasamento" />

                <div class="flex gap-3 mt-6">
                    <x-button type="button" variant="outline" class="flex-1"
                        @click="modalCadastro = false">Cancelar</x-button>
                    <x-button type="submit" class="flex-1">Salvar</x-button>
                </div>
            </form>
        </x-modal>

        {{-- Modais de edição (um por fornecedor) --}}
        @foreach ($fornecedores as $fornecedor)
            @include('fornecedores-casamento.editar-modal', ['casamento' => $casamento, 'fornecedor' => $fornecedor, 'pessoas' => $pessoas])
        @endforeach

        {{-- Modais de parcelas (um por fornecedor contratado) --}}
        @foreach ($fornecedores->where('statusFornecedorCasamento', 'CONTRATADO') as $fornecedor)
            @include('fornecedores-casamento.parcelas-modal', ['casamento' => $casamento, 'fornecedor' => $fornecedor])
        @endforeach
    </main>

@endsection