@php
    $moeda = fn($valor) => 'R$ ' . number_format($valor, 2, ',', '.');
    $coresPagamento = [
        'PAGO' => 'bg-success/10 text-success',
        'PENDENTE' => 'bg-warning/10 text-warning',
        'ATRASADO' => 'bg-danger/10 text-danger',
    ];

    $rotulosPagamento = ['PAGO' => 'Pago', 'PENDENTE' => 'Pendente', 'ATRASADO' => 'Atrasado'];

    $parcelas = $fornecedor->pagamentos;
    $somaParcelas = $parcelas->sum('valorParcela');
    $totalPago = $parcelas->where('statusPagamento', 'PAGO')->sum('valorParcela');
    $somaDiferente = $parcelas->isNotEmpty() && round($somaParcelas * 100) !== round($fornecedor->valorTotalFornecedorCasamento * 100);
@endphp

<x-modal show="modalParcelas === {{ $fornecedor->id }}" onClose="modalParcelas = null">
    <h2 class="titulo text-center mb-1">Parcelas</h2>
    <p class="text-center text-text-muted text-sm mb-4">
        {{ $fornecedor->nomeFornecedorCasamento }} · Total {{ $moeda($fornecedor->valorTotalFornecedorCasamento) }}
    </p>

    @if ($parcelas->isEmpty())
        {{-- formulário pra gerar parcelas quando não tiver--}}
        <form method="POST" action="{{ route('pagamento.gerar', [$casamento, $fornecedor]) }}">
            @csrf
            <div class="flex flex-col gap-2">
                <label for="totalParcelas-{{ $fornecedor->id }}" class="text-primary">Número de parcelas*</label>
                <input type="number" min="1" max="60" step="1" id="totalParcelas-{{ $fornecedor->id }}" name="totalParcelas"
                    value="{{ old('totalParcelas', $fornecedor->totalParcelas) }}" required class="field-input">
            </div>

            <div class="flex flex-col gap-2 mt-4">
                <label for="primeiroVencimento-{{ $fornecedor->id }}" class="text-primary">Vencimento da 1ª parcela*</label>
                <input type="date" id="primeiroVencimento-{{ $fornecedor->id }}" name="primeiroVencimento"
                    value="{{ old('primeiroVencimento') }}" required class="field-input">
                <span class="text-xs text-text-muted">As demais vencem no mesmo dia dos meses seguintes.</span>
            </div>

            <div class="flex gap-3 mt-6">
                <x-button type="button" variant="outline" class="flex-1" @click="modalParcelas = null">Cancelar</x-button>
                <x-button type="submit" class="flex-1">Gerar parcelas</x-button>
            </div>
        </form>
    @else
        <p class="text-sm text-primary mb-3">
            Pago: <strong>{{ $moeda($totalPago) }}</strong> ·
            Falta: <strong>{{ $moeda($somaParcelas - $totalPago) }}</strong>
        </p>

        @if ($somaDiferente)
            <p class="text-xs text-danger mb-3">
                Atenção: a soma das parcelas ({{ $moeda($somaParcelas) }}) é diferente do valor total do fornecedor.
                Use "Refazer parcelas" para recalcular.
            </p>
        @endif

        <div class="flex flex-col gap-2">
            @foreach ($parcelas as $parcela)
                    @php $status = $parcela->statusReal(); @endphp
                    <div class="border-2 border-primary/30 rounded-xl p-3 flex items-center justify-between gap-2">
                        <div>
                            <p class="font-semibold text-primary">{{ $parcela->numeroParcela }}/{{ $parcelas->count() }} ·
                                {{ $moeda($parcela->valorParcela) }}
                            </p>

                            <p class="text-xs text-text-muted">
                                Vence em {{ $parcela->dataVencimento?->format('d/m/Y') ?? '—' }}
                                @if ($parcela->dataPagamento)
                                    · pago em {{ $parcela->dataPagamento->format('d/m/Y') }}
                                @endif
                            </p>
                        </div>

                        <div class="flex items-center gap-2">
                            <span
                                class="text-xs font-semibold px-3 py-1 rounded-full {{ $coresPagamento[$status] }}">{{ $rotulosPagamento[$status] }}</span>

                            @if ($parcela->statusPagamento === 'PAGO')
                                <form method="POST" action="{{ route('pagamento.desfazer', [$casamento, $fornecedor, $parcela]) }}">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="icone-acao cursor-pointer" title="Desfazer pagamento">
                                        <i class="fa-solid fa-rotate-left"></i>
                                    </button>
                                </form>
                            @else
                                <form method="POST" action="{{ route('pagamento.pagar', [$casamento, $fornecedor, $parcela]) }}">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="icone-acao cursor-pointer" title="Marcar como paga">
                                        <i class="fa-solid fa-check"></i>
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
            @endforeach
        </div>

        <div class="flex gap-3 mt-6">
            <form method="POST" action="{{ route('pagamento.reiniciar', [$casamento, $fornecedor]) }}"
                onsubmit="return confirm('Apagar todas as parcelas de {{ $fornecedor->nomeFornecedorCasamento }}? Os pagamentos registrados serão perdidos.')">
                @csrf
                @method('DELETE')
                <x-button type="submit" variant="outline">Refazer parcelas</x-button>
            </form>
            <x-button type="button" class="flex-1" @click="modalParcelas = null">Fechar</x-button>
        </div>

    @endif
</x-modal>