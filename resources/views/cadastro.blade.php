@extends('layouts.app')

@section('content')

    <x-navbar />

    <main>
        <div class="flex justify-center py-12">
            <x-card-sketch class="w-full max-w-2xl">
                <h1 class="titulo">Cadastre-se</h1>

                <form method="POST" action="{{ route('cadastro.store') }}">
                    @csrf
                    <x-input label="Nome" name="name" />

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <x-input label="Telefone" name="telefone" x-data x-mask="(99) 99999-9999" inputmode="numeric"/>

                        <x-input label="CPF" name="cpf" x-data x-mask="999.999.999-99" inputmode="numeric"/>
                    </div>

                    <x-input label="Cidade" name="cidade" />

                    <x-user-type-selector name="tipoUsuario" />

                    <x-input label="E-mail" name="email" type="email" />

                    <x-input label="Senha" name="password" type="password" minlength="8" maxlength="14" required />

                    <x-input label="Confirmar Senha" name="password_confirmation" minlength="8" maxlength="14"
                        type="password" />

                    <div class="password-rules">

                        <p>Sua senha deve conter:</p>

                        <ul>
                            <li id="rule-length">
                                No mínimo 8 caracteres
                            </li>

                            <li id="rule-uppercase">
                                Pelo menos uma letra maiúscula
                            </li>

                            <li id="rule-number">
                                Pelo menos um número
                            </li>

                            <li id="rule-special">
                                Um caractere especial
                            </li>
                        </ul>

                    </div>

                    <x-button type="submit" class="py-3 mt-6 w-full">
                        Cadastrar
                    </x-button>
                </form>

            </x-card-sketch>
        </div>
    </main>


@endsection