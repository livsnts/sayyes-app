{{-- navbar/casal-links.blade.php --}}

@php
    $casamentoAtivo = auth()->user()->casamentos()->where('statusCasamento', 'ATIVO')->first();
@endphp

<div class="flex flex-col gap-4 md:flex-row md:gap-20">

    <a href="/inicio" class="nav-link">
        Início
    </a>

    <a href="/financas" class="nav-link">
        Finanças
    </a>

    <a href="/blog" class="nav-link">
        Blog dos noivos
    </a>

    @if ($casamentoAtivo)
        <a href="{{ route('casamento.show', $casamentoAtivo) }}" class="nav-link">
            Meu casamento
        </a>
    @else
        <a href="{{ route('casamento.create') }}" class="nav-link">
            Criar casamento
        </a>
    @endif

</div>