@extends('layouts.app')

@section('content')
    <x-navbar />

    @if (session('erro'))
        <div class="max-w-2xl mx-auto px-6 pt-6">
            <div class="px-4 py-3 rounded-lg bg-danger/10 border border-danger text-danger text-sm">
                {{ session('erro') }}
            </div>
        </div>
    @endif

    <main class="max-w-2xl mx-auto px-6 py-10">
        <h1 class="titulo">Criar Casamento</h1>

        <x-card-sketch>
            <form method="POST" action="{{ route('casamento.store') }}" enctype="multipart/form-data">
                @csrf

                <x-input label="Nome do casamento*" name="nomeCasamento" required placeholder="Ex.: Casamento G&L" />

                <x-input label="Data*" name="dataCasamento" type="date" required />

                <x-input label="Local" name="localCasamento" placeholder="Ex.: Igreja Matriz" />

                <div class="flex flex-col gap-2 mt-4">
                    <label for="orcamentoTotal" class="text-primary">Orçamento total*</label>
                    <input type="text" 
                    name="orcamentoTotal" 
                    id="orcamentoTotal" 
                    inputmode="numeric" 
                    placeholder="R$ 0,00"
                    value="{{ old('orcamentoTotal') }}" x-data x-mask:dynamic="'R$ ' + $money($input, ',', '.', 2)"
                    class="w-full px-4 py-3 rounded-lg border-2 border-primary bg-transparent outline-none transition-all duration-200 focus:ring-2 focus:ring-primary/20">
                    @error('orcamentoTotal')
                        <span class="text-danger text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <x-input label="Link da lista de presentes" name="urlListaDePresentes" type="url"
                    placeholder="https://..." />

                <div class="flex flex-col gap-2 mt-4">
                    <label for="descricaoCasamento" class="text-primary">Descrição </label>
                    <textarea name="descricaoCasamento" id="descricaoCasamento" rows="3"
                        placeholder="Conte um pouco sobre o casamento ou sobre os noivos..."
                        class="w-full px-4 py-3 rounded-lg border-2 border-primary bg-transparent outline-none resize-none transition-all duration-200 focus:ring-2 focus:ring-primary/20">{{ old('descricaoCasamento') }}</textarea>
                    @error('descricaoCasamento')
                        <span class="text-danger text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div class="flex flex-col gap-2 mt-4">
                    <label for="imagemCasamento" class="text-primary">Imagem (Ex.: Foto dos noivos, foto da igreja, etc.)</label>
                    <input type="file" name="imagemCasamento" id="imagemCasamento" accept="image/*"
                        class="text-sm border-2 border-primary rounded-lg cursor-pointer bg-transparent file:bg-primary file:text-white file:px-4 file:py-2 file:border-none file:cursor-pointer hover:file:bg-primary/80 transition-all duration-200">
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