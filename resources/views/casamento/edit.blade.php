@extends('layouts.app')

@section('content')
    <x-navbar />

    <main class="max-w-2xl mx-auto px-6 py-10">
        <h1 class="titulo">Editar Casamento</h1>

        <x-card-sketch>
            <form method="POST" action="{{ route('casamento.update', $casamento) }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="flex flex-col gap-2 mt-4">
                    <label for="statusCasamento" class="text-primary">Status</label>
                    <select name="statusCasamento" id="statusCasamento"
                        class="w-full px-4 py-3 rounded-lg border-2 border-primary bg-transparent outline-none transition-all duration-200 focus:ring-2 focus:ring-primary/20">
                        <option value="ATIVO" {{ old('statusCasamento', $casamento->statusCasamento) === 'ATIVO' ? 'selected' : '' }}>Ativo</option>
                        <option value="REALIZADO" {{ old('statusCasamento', $casamento->statusCasamento) === 'REALIZADO' ? 'selected' : '' }}>Realizado</option>
                        <option value="CANCELADO" {{ old('statusCasamento', $casamento->statusCasamento) === 'CANCELADO' ? 'selected' : '' }}>Cancelado</option>
                        <option value="INATIVO" {{ old('statusCasamento', $casamento->statusCasamento) === 'INATIVO' ? 'selected' : '' }}>Inativo</option>
                    </select>
                    @error('statusCasamento')
                        <span class="text-danger text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <x-input label="Nome do casamento*" name="nomeCasamento" required />
                @error('nomeCasamento')
                    <span class="text-danger text-sm">{{ $message }}</span>
                @enderror

                <x-input label="Data*" name="dataCasamento" type="date" required />

                <x-input label="Local" name="localCasamento" />

                <div class="flex flex-col gap-2 mt-4">
                    <label for="orcamentoTotal" class="text-primary">Orçamento total*</label>
                    <input type="text" name="orcamentoTotal" id="orcamentoTotal" inputmode="numeric"
                        value="{{ old('orcamentoTotal') }}" x-data x-mask:dynamic="'R$ ' + $money($input, ',', '.', 2)"
                        class="w-full px-4 py-3 rounded-lg border-2 border-primary bg-transparent outline-none transition-all duration-200 focus:ring-2 focus:ring-primary/20">
                    @error('orcamentoTotal')
                        <span class="text-danger text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <x-input label="Link da lista de presentes" name="urlListaDePresentes" type="url" />

                <div class="flex flex-col gap-2 mt-4">
                    <label for="descricaoCasamento" class="text-primary">Descrição</label>
                    <textarea name="descricaoCasamento" id="descricaoCasamento" rows="3"
                        class="w-full px-4 py-3 rounded-lg border-2 border-primary bg-transparent outline-none resize-none transition-all duration-200 focus:ring-2 focus:ring-primary/20">{{ old('descricaoCasamento', $casamento->descricaoCasamento) }}</textarea>
                    @error('descricaoCasamento')
                        <span class="text-danger text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div class="flex flex-col gap-2 mt-4">
                    <label class="text-primary">Foto do casal (opcional)</label>
                    @if ($casamento->imagemCasamento && file_exists(storage_path('app/public/' . $casamento->imagemCasamento)))
                        <img src="{{ asset('storage/' . $casamento->imagemCasamento) }}"
                            class="w-32 h-32 object-cover rounded-lg mb-2">
                    @endif
                    <input type="file" name="imagemCasamento" accept="image/*"
                        class="text-sm border-2 border-primary rounded-lg cursor-pointer bg-transparent file:bg-primary file:text-white file:px-4 file:py-2 file:border-none file:cursor-pointer hover:file:bg-primary/80 transition-all duration-200">
                    @error('imagemCasamento')
                        <span class="text-danger text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div class="flex gap-3 mt-6">
                    <a href="{{ route('casamento.show', $casamento) }}"
                        class="flex-1 text-center py-3 rounded-lg border-2 border-primary text-primary font-semibold transition hover:bg-primary/10">
                        Cancelar
                    </a>

                    <x-button type="submit" class="flex-1">
                        Salvar alterações
                    </x-button>
                </div>
            </form>
        </x-card-sketch>
    </main>
@endsection