@props(['casamento', 'convidado'])

<x-modal show="modalEditar === {{ $convidado->id }}" onClose="modalEditar = null">
    <h3 class="titulo text-center mb-4">Editar convidado</h3>

    <form method="POST" action="{{ route('convidado.update', [$casamento, $convidado]) }}" x-data="{ acompanhantes: @js($convidado->acompanhantes->map(fn($a) => [
        'id' => $a->id,
        'nome' => $a->nomeAcompanhante,
        'faixaEtaria' => $a->faixaEtariaAcompanhante?->value,
        'genero' => $a->generoAcompanhante?->value,
        'status' => $a->statusAcompanhante,
    ])) }">
        @csrf
        @method('PUT')

        <x-input label="Nome do convidado*" name="nomeConvidado" :value="$convidado->nomeConvidado" required />

        <x-input label="Telefone / Whatsapp" name="telefoneConvidado" :value="$convidado->telefoneConvidado"
            x-mask="(99) 99999-9999" inputmode="numeric" />

        <div class="flex flex-col gap-2 mt-4" x-data="{ status: '{{ $convidado->statusConvidado }}' }">
            <label for="statusConvidado-{{ $convidado->id }}" class="text-primary">Status</label>
            <select id="statusConvidado-{{ $convidado->id }}" name="statusConvidado" x-model="status"
                class="field-input" :class="{
            'text-warning': status === 'PENDENTE',
            'text-success': status === 'CONFIRMADO',
            'text-danger': status === 'RECUSADO'
        }">
                @foreach (['PENDENTE' => ['Pendente', 'text-warning'], 'CONFIRMADO' => ['Confirmado', 'text-success'], 'RECUSADO' => ['Recusado', 'text-danger']] as $valor => [$rotulo, $cor])
                    <option value="{{ $valor }}" class="{{ $cor }}" @selected($convidado->statusConvidado === $valor)>
                        {{ $rotulo }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="grid grid-cols-2 gap-3 mt-4">
            <div class="flex flex-col gap-2">
                <label for="faixaEtariaConvidado-{{ $convidado->id }}" class="text-primary">Faixa etária</label>
                <select id="faixaEtariaConvidado-{{ $convidado->id }}" name="faixaEtariaConvidado" class="field-input">
                    <option value="">Não informada</option>
                    @foreach (\App\Enums\FaixaEtaria::cases() as $faixa)
                        <option value="{{ $faixa->value }}" @selected($convidado->faixaEtariaConvidado === $faixa)>
                            {{ $faixa->value }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="flex flex-col gap-2">
                <label for="generoConvidado-{{ $convidado->id }}" class="text-primary">Gênero</label>
                <select id="generoConvidado-{{ $convidado->id }}" name="generoConvidado" class="field-input">
                    <option value="">Não informado</option>
                    @foreach (\App\Enums\Genero::cases() as $genero)
                        <option value="{{ $genero->value }}" @selected($convidado->generoConvidado === $genero)>
                            {{ $genero->value }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="flex flex-col gap-2 mt-4">
            <label for="observacoes-{{ $convidado->id }}" class="text-primary">Observações da confirmação</label>
            <textarea id="observacoes-{{ $convidado->id }}" name="observacoesConfirmacao" rows="2"
                class="field-input">{{ $convidado->observacoesConfirmacao }}</textarea>
        </div>

        <div class="mt-4">
            <div class="flex items-center justify-between">
                <label class="text-primary">Acompanhantes</label>
                <button type="button"
                    @click="acompanhantes.push({ nome: '', faixaEtaria: '', genero: '', status: 'PENDENTE' })"
                    class="text-sm font-semibold text-primary flex items-center gap-1 cursor-pointer">
                    <i class="fa-solid fa-plus"></i> Adicionar acompanhante
                </button>
            </div>

            <template x-for="(acompanhante, indice) in acompanhantes" :key="indice">
                <div class="flex gap-2 items-start mt-3 border border-primary/20 rounded-lg p-3">
                    <div class="flex-1 flex flex-col gap-2">
                        <input type="hidden" :name="`acompanhantes[${indice}][id]`" :value="acompanhante.id ?? ''">
                        <input type="text" :name="`acompanhantes[${indice}][nome]`" x-model="acompanhante.nome"
                            placeholder="Nome do acompanhante" class="field-input w-full">
                        <div class="grid grid-cols-2 gap-2">
                            <select :name="`acompanhantes[${indice}][faixaEtaria]`" x-model="acompanhante.faixaEtaria"
                                class="field-input">
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
                        <select :name="`acompanhantes[${indice}][status]`" x-model="acompanhante.status"
                            class="field-input" :class="{
                                'text-warning': acompanhante.status === 'PENDENTE',
                                'text-success': acompanhante.status === 'CONFIRMADO',
                                'text-danger': acompanhante.status === 'RECUSADO'
                            }">
                            <option value="PENDENTE" class="text-warning">Pendente</option>
                            <option value="CONFIRMADO" class="text-success">Confirmado</option>
                            <option value="RECUSADO" class="text-danger">Recusado</option>
                        </select>
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
        </div>

        <x-button type="submit" class="w-full mt-6">Salvar alterações</x-button>
    </form>
</x-modal>