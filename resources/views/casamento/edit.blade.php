@extends('layouts.app')

@section('content')
    <x-navbar />

    <main class="page-main">
        <h1 class="titulo">Editar Casamento</h1>

        <x-card-sketch>
            <form method="POST" action="{{ route('casamento.update', $casamento) }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="flex flex-col gap-2 mt-4">
                    <label for="statusCasamento" class="text-primary">Status</label>
                    <select name="statusCasamento" id="statusCasamento" class="field-input">
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

                <x-input label="Data*" name="dataCasamento" type="date" required />

                <x-input label="Local" name="localCasamento" />

                <div class="flex flex-col gap-2 mt-4">
                    <label for="orcamentoTotal" class="text-primary">Orçamento total*</label>
                    <input type="text" name="orcamentoTotal" id="orcamentoTotal" inputmode="numeric"
                        value="{{ old('orcamentoTotal') }}" x-data x-mask:dynamic="'R$ ' + $money($input, ',', '.', 2)"
                        class="field-input">
                    @error('orcamentoTotal')
                        <span class="text-danger text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <x-input label="Link da lista de presentes" name="urlListaDePresentes" type="url" />

                <x-textarea label="Descrição" name="descricaoCasamento" :value="$casamento->descricaoCasamento" />

                <div class="flex flex-col gap-2 mt-4">
                    <label class="text-primary">Foto do casal (opcional)</label>
                    @if ($casamento->imagemCasamento && file_exists(storage_path('app/public/' . $casamento->imagemCasamento)))
                        <img src="{{ asset('storage/' . $casamento->imagemCasamento) }}"
                            class="w-32 h-32 object-cover rounded-lg mb-2">
                    @endif
                    <input type="file" name="imagemCasamento" accept="image/*" class="file-input">
                    @error('imagemCasamento')
                        <span class="text-danger text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div>
                    <a href="{{ route('casamento.show', $casamento) }}">
                        <x-button variant="outline" class="flex-1">Cancelar</x-button>
                    </a>

                    <x-button type="submit" class="flex-1">
                        Salvar alterações
                    </x-button>
                </div>


                </div>
            </form>
        </x-card-sketch>
    </main>
@endsection