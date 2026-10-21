@props(['estatisticas', 'casamento' => null, 'comFiltro' => false])

@php
    $cards = [
        '' => ['label' => 'Total de pessoas', 'valor' => $estatisticas['totalPessoas'], 'convites' => $estatisticas['totalConvidados'], 'cor' => 'text-primary'],
        'CONFIRMADO' => ['label' => 'Confirmadas', 'valor' => $estatisticas['totalPessoasConfirmadas'], 'convites' => $estatisticas['confirmados'], 'cor' => 'text-success'],
        'PENDENTE' => ['label' => 'Pendentes', 'valor' => $estatisticas['totalPessoasPendentes'], 'convites' => $estatisticas['pendentes'], 'cor' => 'text-warning'],
        'RECUSADO' => ['label' => 'Recusadas', 'valor' => $estatisticas['totalPessoasRecusadas'], 'convites' => $estatisticas['recusados'], 'cor' => 'text-danger'],
    ];

    $tag = $comFiltro ? 'a' : 'div';
@endphp

<div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
    @foreach ($cards as $status => $card)
        <{{ $tag }}
            @if ($comFiltro)
                href="{{ route('convidado.index', array_filter(['casamento' => $casamento, 'status' => $status, 'busca' => request('busca')])) }}"
            @endif
            class="card-resumo block transition {{ $comFiltro && request('status', '') === (string) $status ? 'bg-secondary/30' : '' }}">
            <p class="card-resumo-valor {{ $card['cor'] }}">{{ $card['valor'] }}</p>
            <p class="card-resumo-label">{{ $card['label'] }}</p>
            <p class="text-text-muted text-xs">
                {{ $card['convites'] }} {{ $card['convites'] === 1 ? 'convite' : 'convites' }}
            </p>
        </{{ $tag }}>
    @endforeach
</div>