@props(['casamento', 'convidado'])

<div {{ $attributes->merge(['class' => 'flex gap-2']) }}>
    @if ($convidado->statusConvidado === 'PENDENTE' && $convidado->telefoneConvidado)
        <a href="{{ $convidado->linkConfirmacaoWhatsapp() }}" target="_blank"
            class="w-9 h-9 rounded-lg bg-primary text-white flex items-center justify-center hover:opacity-80 shrink-0"
            title="Enviar confirmação via WhatsApp">
            <i class="fa-brands fa-whatsapp"></i>
        </a>
    @endif
    <form action="{{ route('convidado.destroy', [$casamento, $convidado]) }}" method="POST"
        onsubmit="return confirm('Remover {{ $convidado->nomeConvidado }}?');">
        @csrf
        @method('DELETE')
        <button type="submit"
            class="w-9 h-9 rounded-lg bg-primary text-white flex items-center justify-center hover:opacity-80 shrink-0"
            title="Remover convidado">
            <i class="fa-regular fa-trash-can"></i>
        </button>
    </form>
</div>