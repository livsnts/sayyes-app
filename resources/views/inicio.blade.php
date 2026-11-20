@extends('layouts.app')

@section('content')

    <section class="max-w-7xl mx-auto px-8 py-12">

        <div class="flex flex-col lg:flex-row items-center justify-center gap-20">

            {{-- Bolo --}}
            <div class="floating-cake">
                <img src="{{ asset('images/homepage-noivos.png') }}" alt="Casamento" class="w-94">
            </div>

            {{-- Texto --}}
            <div class="max-w-xl text-center lg:text-left">

                <h1 class="font-primary text-primary text-4xl lg:text-6xl font-bold leading-tight">
                    Menos planilhas,<br>
                    Mais memórias.
                </h1>

                <p class="mt-6 text-primary text-lg ">
                    Organize convidados, fornecedores
                    e finanças do seu casamento
                    em um só lugar.
                </p>

            </div>

        </div>

    </section>

    <section class="max-w-7xl mx-auto px-8 py-10">
        <p class="font-secondary font-bold text-secondary text-sm">
            O que o SayYes faz por você?
        </p>

        <h2 class="mt-2 font-primary font-bold text-primary text-3xl lg:text-4xl leading-tight">
            Tudo que o seu casamento precisa, em um só lugar
        </h2>

        @php
            $funcionalidades = [
                [
                    'imagem' => 'images/doodles/conviteinicio.png',
                    'titulo' => 'Confirmação de convidados',
                    'texto' => 'Link único por convidado enviado via WhatsApp. Confirmação com acompanhantes em segundos, sem app extra.',
                ],
                [
                    'imagem' => 'images/doodles/violino.png',
                    'titulo' => 'Gestão de fornecedores',
                    'texto' => 'Cadastre buffet, fotógrafo, decoração e todos os parceiros. Controle contratos e valores em um único painel.',
                ],
                [
                    'imagem' => 'images/doodles/moeda.png',
                    'titulo' => 'Controle de parcelas',
                    'texto' => 'Registre cada parcela, acompanhe vencimentos e saiba exatamente o que está em atraso antes que vire problema.',
                ],
                [
                    'imagem' => 'images/doodles/coes.png',
                    'tamanho' => 'h-40 scale-150',
                    'titulo' => 'Vínculo com o parceiro(a)',
                    'texto' => 'Noivos e assessor acessam o mesmo casamento, cada um com a sua conta. Vincule o parceiro(a) só pelo e-mail.',
                ],
            ];
        @endphp

        <div class="mt-10 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">

            @foreach ($funcionalidades as $item)
                <div class="flex flex-col items-center text-center border-2 border-primary rounded-[2.5rem] px-6 py-8">

                    <img src="{{ asset($item['imagem']) }}" alt="{{ $item['titulo'] }}"
                        class="object-contain {{ $item['tamanho'] ?? 'h-42' }}">

                    <h3 class="mt-5 font-primary font-bold text-primary text-base">
                        {{ $item['titulo'] }}
                    </h3>

                    <p class="mt-3 font-secondary text-primary text-xs leading-relaxed">
                        {{ $item['texto'] }}
                    </p>

                </div>
            @endforeach

        </div>
    </section>

    {{-- Seção: Para quem é --}}
    <section class="max-w-7xl mx-auto px-8 py-10">

        <p class="font-secondary font-bold text-secondary text-sm">
            Para quem é?
        </p>

        <h2 class="mt-2 font-primary font-bold text-primary text-3xl lg:text-4xl leading-tight">
            Feito para quem organiza o grande dia
        </h2>

        @php
            $perfis = [
                [
                    'imagens' => ['images/doodles/fusca.png'],
                    'titulo' => 'Noivos',
                    'texto' => 'Tenha a lista de convidados, os fornecedores e as parcelas num só lugar, sem planilhas espalhadas.',
                ],
                [
                    'imagens' => ['images/doodles/prancheta.png'],
                    'titulo' => 'Assessores',
                    'texto' => 'Acompanhe o casamento dos seus clientes e ajude o casal a manter tudo em dia.',
                ],
            ];
        @endphp

        <div class="mt-10 mx-auto grid grid-cols-1 md:grid-cols-2 gap-6 max-w-4xl">

            @foreach ($perfis as $perfil)
                <div class="flex flex-col items-center text-center border-2 border-primary rounded-[2.5rem] px-8 py-10">

                    <div class="flex items-end justify-center gap-2 h-32">
                        @foreach ($perfil['imagens'] as $imagem)
                            <img src="{{ asset($imagem) }}" alt="{{ $perfil['titulo'] }}" class="h-full object-contain">
                        @endforeach
                    </div>

                    <h3 class="mt-5 font-primary font-bold text-primary text-xl">
                        {{ $perfil['titulo'] }}
                    </h3>

                    <p class="mt-3 font-secondary text-primary text-sm leading-relaxed">
                        {{ $perfil['texto'] }}
                    </p>

                </div>
            @endforeach

        </div>

    </section>

    {{-- Seção: Chamada para ação (CTA) --}}
    @guest
        <section class="max-w-7xl mx-auto px-8 py-10">

            <div class="bg-primary rounded-[2.5rem] px-8 py-14 text-center">

                <h2 class="font-primary font-bold text-background text-3xl lg:text-4xl">
                    Pronto para dizer sim à organização?
                </h2>

                <p class="mt-4 font-secondary text-background text-lg">
                    Crie sua conta e comece a planejar o seu casamento hoje.
                </p>

                <div class="mt-8 flex flex-col sm:flex-row items-center justify-center gap-4">

                    <a href="{{ route('cadastro') }}"
                        class="px-8 py-2 rounded-lg border-2 border-background bg-background text-primary font-bold transition-colors duration-200 hover:opacity-90">
                        Criar conta
                    </a>

                    <a href="{{ route('login') }}"
                        class="px-8 py-2 rounded-lg border-2 border-background text-background font-bold transition-colors duration-200 hover:bg-background hover:text-primary">
                        Entrar
                    </a>

                </div>

            </div>

        </section>
    @endguest
@endsection