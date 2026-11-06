@props(['casamento', 'fornecedor', 'pessoas'])

@php
    // sem "R$ " no início, igual à tela de editar casamento; a máscara coloca o prefixo
    $formatar = fn($valor) => $valor !== null ? number_format($valor, 2, ',', '.') : '';
@endphp

<x-modal show="modalEditar === {{ $fornecedor->id }}" onClose="modalEditar = null">
    <h2 class="titulo text-center mb-4">Editar fornecedor</h2>

    <form method="POST" action="{{ route('fornecedor-casamento.update', [$casamento, $fornecedor]) }}" x-data="{
        tipo: '{{ $fornecedor->tipoValorFornecedorCasamento->value }}',
        telefone: @js($fornecedor->telefoneFornecedorCasamento ?? ''),
        adulto: @js($formatar($fornecedor->valorAdultoFornecedorCasamento)),
        crianca: @js($formatar($fornecedor->valorCriancaFornecedorCasamento)),
        qtdAdultos: @js($fornecedor->quantidadeAdultosFornecedorCasamento ?? ''),
        qtdCriancas: @js($fornecedor->quantidadeCriancasFornecedorCasamento ?? ''),
        total: @js($formatar($fornecedor->valorTotalFornecedorCasamento)),
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
        @method('PUT')

        <x-input label="Nome*" name="nomeFornecedorCasamento" :value="$fornecedor->nomeFornecedorCasamento" required />

        <div class="flex flex-col gap-2 mt-4">
            <label for="categoria-{{ $fornecedor->id }}" class="text-primary">Categoria*</label>
            <select id="categoria-{{ $fornecedor->id }}" name="categoriaFornecedorCasamento" required
                class="field-input">
                @foreach (\App\Enums\CategoriaFornecedor::cases() as $categoria)
                    <option value="{{ $categoria->value }}"
                        @selected($fornecedor->categoriaFornecedorCasamento?->value === $categoria->value)>
                        {{ $categoria->value }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="flex flex-col gap-2 mt-4">
            <label for="telefone-{{ $fornecedor->id }}" class="text-primary">Telefone</label>
            <input type="text" id="telefone-{{ $fornecedor->id }}" name="telefoneFornecedorCasamento" x-model="telefone"
                x-mask="(99) 99999-9999" inputmode="numeric" class="field-input">
        </div>

        <div class="flex flex-col gap-2 mt-4">
            <label for="status-{{ $fornecedor->id }}" class="text-primary">Status*</label>
            <select id="status-{{ $fornecedor->id }}" name="statusFornecedorCasamento" class="field-input">
                @foreach (\App\Models\FornecedorCasamento::STATUS as $chave => $texto)
                    <option value="{{ $chave }}" @selected($fornecedor->statusFornecedorCasamento === $chave)>
                        {{ $texto }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="flex flex-col gap-2 mt-4">
            <label for="tipo-{{ $fornecedor->id }}" class="text-primary">Tipo de valor*</label>
            <select id="tipo-{{ $fornecedor->id }}" name="tipoValorFornecedorCasamento" x-model="tipo"
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
                <label for="valorAdulto-{{ $fornecedor->id }}" class="text-primary">Valor por adulto*</label>
                <input type="text" id="valorAdulto-{{ $fornecedor->id }}" name="valorAdulto" inputmode="numeric"
                    x-model="adulto" :disabled="tipo !== 'POR_PESSOA'"
                    x-mask:dynamic="'R$ ' + $money($input, ',', '.', 2)" class="field-input">
            </div>
            <div class="flex flex-col gap-2 mt-4">
                <label for="valorCrianca-{{ $fornecedor->id }}" class="text-primary">Valor por criança*</label>
                <input type="text" id="valorCrianca-{{ $fornecedor->id }}" name="valorCrianca" inputmode="numeric"
                    x-model="crianca" :disabled="tipo !== 'POR_PESSOA'"
                    x-mask:dynamic="'R$ ' + $money($input, ',', '.', 2)" class="field-input">
            </div>
            <div class="flex flex-col gap-2 mt-4">
                <label for="quantidadeAdultos-{{ $fornecedor->id }}" class="text-primary">Quantidade de adultos*</label>
                <input type="number" min="0" step="1" id="quantidadeAdultos-{{ $fornecedor->id }}"
                    name="quantidadeAdultos" x-model="qtdAdultos" :disabled="tipo !== 'POR_PESSOA'" class="field-input">
            </div>
            <div class="flex flex-col gap-2 mt-4">
                <label for="quantidadeCriancas-{{ $fornecedor->id }}" class="text-primary">Quantidade de
                    crianças*</label>
                <input type="number" min="0" step="1" id="quantidadeCriancas-{{ $fornecedor->id }}"
                    name="quantidadeCriancas" x-model="qtdCriancas" :disabled="tipo !== 'POR_PESSOA'"
                    class="field-input">
            </div>
        </div>

        <div class="flex flex-col gap-2 mt-4">
            <label for="valorTotal-{{ $fornecedor->id }}" class="text-primary">Valor total*</label>
            <input type="text" id="valorTotal-{{ $fornecedor->id }}" name="valorTotal" inputmode="numeric"
                x-model="total" x-mask:dynamic="'R$ ' + $money($input, ',', '.', 2)" class="field-input">
            <span class="text-xs text-text-muted" x-show="tipo === 'POR_PESSOA'" x-cloak>
                Calculado como (valor por adulto × quantidade de adultos) + (valor por criança × quantidade de
                crianças). Você pode editar o valor.
            </span>
        </div>

        <x-textarea label="Observações" name="observacoesFornecedorCasamento"
            :value="$fornecedor->observacoesFornecedorCasamento" />

        <div class="flex gap-3 mt-6">
            <x-button type="button" variant="outline" class="flex-1" @click="modalEditar = null">Cancelar</x-button>
            <x-button type="submit" class="flex-1">Salvar alterações</x-button>
        </div>
    </form>
</x-modal>