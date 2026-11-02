{{-- navbar/casal-links.blade.php --}}

@php
    $usuario = auth()->user();
    $totalCasamentos = $usuario->casamentos()->count();
    $temAtivo = $usuario->casamentos()->where('statusCasamento', 'ATIVO')->exists();
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

    @if ($totalCasamentos > 0)
        <a href="{{ route('casamento.index') }}" class="nav-link">
            {{ $totalCasamentos > 1 ? 'Meus casamentos' : 'Meu casamento' }}
        </a>
    @endif

    @unless ($temAtivo)
        <a href="{{ route('casamento.create') }}" class="nav-link">
            Criar casamento
        </a>
    @endunless

</div>