<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="{{ asset('images/logo-sayyes.png') }}">
    <script src="https://kit.fontawesome.com/6c3e433ed5.js" crossorigin="anonymous"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <title>Confirmar presença - {{ $convidado->casamento->nomeCasamento }}</title>
</head>

<body class="font-primary">
    <x-flash-messages />

    <main class="flex flex-col items-center px-4 py-8 page-main-wide">
        <img src="{{ asset('images/logo-sayyes.png') }}" alt="Say Yes" class="h-16 mb-6">

        <x-card-sketch class="w-full max-w-md">
            <h3 class="text-center text-primary text-3xl">Olá, {{ $convidado->nomeConvidado }}!</h3>

            <p class="text-primary mt-2">
                Você foi convidado(a) para o <strong>{{ $convidado->casamento->nomeCasamento }}</strong>
                @if ($convidado->casamento->dataCasamento)
                    em {{ $convidado->casamento->dataCasamento->format('d/m/Y') }}
                @endif
                @if ($convidado->casamento->localCasamento)
                    - {{ $convidado->casamento->localCasamento }}
                @endif
                .
            </p>

             <form method="POST" action="{{ route('convidado.salvar-confirmacao', $convidado->tokenConfirmacao) }}">
                @csrf

                <p class="text-primary font-semibold mt-6 mb-2">Quem vai comparecer?</p>

                <div class="flex flex-col gap-3">

                    {{-- convidado principal --}}
                    <label class="flex items-center gap-3 text-primary cursor-pointer">
                        <input type="checkbox" name="convidadoVai" value="1"
                            class="size-5 accent-primary"
                            @checked(old('convidadoVai', $convidado->statusConvidado === 'CONFIRMADO'))>
                            <span>{{ $convidado->nomeConvidado }} <span class="text-sm opacity-70">(você)</span></span>
                    </label>

                    {{-- acompanhantes --}}
                    @foreach ($convidado->acompanhantes as $acompanhante)
                        <label class="flex items-center gap-3 text-primary cursor-pointer">
                            <input type="checkbox" name="acompanhantesQueVao[]" value="{{ $acompanhante->id }}"
                                class="size-5 accent-primary"
                                @checked(in_array($acompanhante->id, old('acompanhantesQueVao', $convidado->acompanhantes->where('statusAcompanhante', 'CONFIRMADO')->pluck('id')->all())))>
                            <span>{{ $acompanhante->nomeAcompanhante }}</span>
                        </label>
                    @endforeach
                </div>
             </form>
        </x-card-sketch>
    </main>
</body>

</html>