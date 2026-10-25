@php
    $casamentoAtual = request()->route('casamento');
@endphp

@if ($casamentoAtual instanceof \App\Models\Casamento)
    <nav aria-label="Navegação do casamento" class="px-4 py-4 text-sm text-text-muted/80">
        <ol class="flex items-center gap-2 flex-wrap">
            <li>
                <a href="{{ route('casamento.index') }}" class="hover:underline">
                    Meus casamentos
                </a>
            </li>
            <li><i class="fa-solid fa-chevron-right text-xs"></i></li>
            <li>
                <a href="{{ route('casamento.show', $casamentoAtual) }}" class="font-bold hover:underline">
                    {{ $casamentoAtual->nomeCasamento }}
                </a>
            </li>
        </ol>
    </nav>
@endif