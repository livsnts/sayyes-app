@extends('layouts.app')

@section('content')
    <x-navbar />

    <main class="page-main">

        <x-flash-messages />

        <h1 class="titulo">Criar Casamento</h1>

        <x-card-sketch>
            <form method="POST" action="{{ route('casamento.store') }}" enctype="multipart/form-data">
                @csrf

                <x-input label="Nome do casamento*" name="nomeCasamento" required placeholder="Ex.: Casamento G&L" />

                <x-input label="Data*" name="dataCasamento" type="date" required />

                <x-input label="Local" name="localCasamento" placeholder="Ex.: Igreja Matriz" />

                <div class="flex flex-col gap-2 mt-4">
                    <label for="orcamentoTotal" class="text-primary">Orçamento total*</label>
                    <input
                        type="text"
                        name="orcamentoTotal"
                        id="orcamentoTotal"
                        inputmode="numeric"
                        placeholder="R$ 0,00"
                        value="{{ old('orcamentoTotal') }}"
                        x-data
                        x-mask:dynamic="'R$ ' + $money($input, ',', '.', 2)"
                        class="field-input"
                    >
                    @error('orcamentoTotal')
                        <span class="text-danger text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <x-input label="Link da lista de presentes" name="urlListaDePresentes" type="url" placeholder="https://..." />

                <x-textarea
                    label="Descrição"
                    name="descricaoCasamento"
                    placeholder="Conte um pouco sobre o casamento ou sobre os noivos..."
                />

                <div class="flex flex-col gap-2 mt-4">
                    <label for="imagemCasamento" class="text-primary">Imagem (Ex.: Foto dos noivos, foto da igreja, etc.)</label>
                    <input type="file" name="imagemCasamento" id="imagemCasamento" accept="image/*" class="file-input">
                    @error('imagemCasamento')
                        <span class="text-danger text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <x-button type="submit" class="mt-6 w-full">
                    Criar casamento
                </x-button>
            </form>
        </x-card-sketch>
    </main>
@endsection
