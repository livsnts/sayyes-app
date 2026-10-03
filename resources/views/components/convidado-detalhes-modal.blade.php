@props(['casamento', 'convidado'])

<x-modal show="modalDetalhes === {{ $convidado->id }}" onClose="modalDetalhes = null">
    <h3 class="titulo text-center mb-4">Detalhes do convidado</h3>

    <p class="text-center text-sm text-primary font-semibold mb-4">
        Link de confirmação:
        <a href="{{ route('convidado.confirmar', $convidado->tokenConfirmacao) }}" target="_blank" class="underline">
            {{ route('convidado.confirmar', $convidado->tokenConfirmacao) }}
        </a>
    </p>

    <div class="space-y-1 text-primary">
        <p>Nome: {{ $convidado->nomeConvidado }}</p>
        <p><strong>Status:</strong> <x-convidado-status :status="$convidado->statusConvidado" /></p>
        <p><strong>Telefone:</strong> {{ $convidado->telefoneConvidado ?? '—' }}</p>
        <p><strong>Data de confirmação:</strong> {{ $convidado->dataConfirmacao?->format('d/m/Y') ?? '—' }}</p>
        <p><strong>Observações da confirmação:</strong> {{ $convidado->observacoesConfirmacao ?? '—' }}</p>
        <p><strong>Alergias:</strong> {{ $convidado->alergiasConvidado ?? '—' }}</p>
    </div>

    @if ($convidado->acompanhantes->isNotEmpty())
        <h3 class="font-bold text-primary mt-4 mb-2">Acompanhantes</h3>
        <div class="space-y-1">
            @foreach ($convidado->acompanhantes as $acompanhante)
                <p class="text-primary">
                    <strong>Nome:</strong> {{ $acompanhante->nomeAcompanhante }} —
                    <strong>Idade:</strong> {{ $acompanhante->idadeAcompanhante ?? '—' }}
                </p>
            @endforeach
        </div>
    @endif

    <div class="flex gap-3 mt-6">
        <form method="POST" action="{{ route('convidado.destroy', [$casamento, $convidado]) }}"
            onsubmit="return confirm('Remover {{ $convidado->nomeConvidado }}?')" class="flex-1">
            @csrf
            @method('DELETE')
            <x-button type="submit" variant="outline" class="w-full">Remover convidado</x-button>
        </form>

        @if ($convidado->telefoneConvidado)
            <a href="{{ $convidado->linkConfirmacaoWhatsapp($casamento->nomeCasamento) }}" target="_blank"
                class="flex-1">
                <x-button type="button" class="w-full">Entrar em contato</x-button>
            </a>
        @endif
    </div>
</x-modal>