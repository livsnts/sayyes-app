@extends('layouts.app')

@section('content')

    <main class="page-main-wide"
        x-data="{ modalEditar: null, modalCadastro: {{ $errors->has('nomeFornecedorConfianca') ? 'true' : 'false' }} }">

        <div class="page-header">
            <div class="page-header-title-row">
                <img src="{{ asset('images/doodles/jantar.png') }}" alt="Convite" class="w-32">
                <h1 class="titulo">Fornecedores de Confiança</h1>
                <button type="button" @click="modalCadastro = true" class="botao-detalhes">
                    <i class="fa-solid fa-plus"></i>
                </button>
            </div>
            <p class="text-text-muted pt-2">
                Sua agenda pessoal de fornecedores. Reaproveite em qualquer casamento que você atender.
            </p>
        </div>

        <form method="GET" action="{{ route('fornecedor-confianca.index') }}" class="flex items-center gap-2 mb-4">
            <label for="categoria" class="text-primary text-sm">Filtrar por categoria:</label>
            <select id="categoria" name="categoria" class="w-58 px-4 py-3 rounded-lg border-2 border-primary bg-transparent outline-none" onchange="this.form.submit()">
                <option value="">Todas as categorias</option>
                @foreach (\App\Enums\CategoriaFornecedor::cases() as $categoria)
                    <option value="{{ $categoria->value }}" @selected(request('categoria') === $categoria->value)>
                        {{ $categoria->value }}
                    </option>
                @endforeach
            </select>

            @if (request('categoria'))
                <a href="{{ route('fornecedor-confianca.index') }}" class="text-primary text-sm underline">Limpar filtro</a>
            @endif
        </form>

        <div class="border-2 border-primary rounded-2xl overflow-hidden bg-background">
            <table class="w-full text-sm">
                <thead class="bg-primary text-white">
                    <tr>
                        <th class="itens-tabela text-left">Nome</th>
                        <th class="itens-tabela text-left">Categoria</th>
                        <th class="itens-tabela text-left">Telefone</th>
                        <th class="itens-tabela text-left">Instagram</th>
                        <th class="itens-tabela text-center">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($fornecedores as $fornecedor)
                        <tr class="border-t border-primary/20">
                            <td class="itens-tabela font-semibold text-primary">{{ $fornecedor->nomeFornecedorConfianca }}</td>
                            <td class="itens-tabela text-primary">{{ $fornecedor->categoriaFornecedorConfianca?->value ?? '—' }}
                            </td>
                            <td class="itens-tabela text-primary">{{ $fornecedor->telefoneFornecedorConfianca ?? '—' }}</td>
                            <td class="itens-tabela text-primary">{{ $fornecedor->instagramFornecedorConfianca ?? '—' }}</td>
                            <td class="itens-tabela">
                                <div class="flex gap-1 justify-center">
                                    <button type="button" @click="modalEditar = {{ $fornecedor->id }}"
                                        class="icone-acao cursor-pointer">
                                        <i class="fa-solid fa-pen"></i>
                                    </button>
                                    <form method="POST" action="{{ route('fornecedor-confianca.destroy', $fornecedor) }}"
                                        onsubmit="return confirm('Remover {{ $fornecedor->nomeFornecedorConfianca }}?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="icone-acao cursor-pointer">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-6 text-center text-text-muted">
                                Nenhum fornecedor cadastrado ainda.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <x-modal show="modalCadastro" onClose="modalCadastro = false">
            <h2 class="titulo text-center mb-4">Adicionar fornecedor</h2>

            <form method="POST" action="{{ route('fornecedor-confianca.store') }}">
                @csrf

                <x-input label="Nome" name="nomeFornecedorConfianca" required placeholder="Ex.: Buffet do João" />

                <div class="flex flex-col gap-2 mt-4">
                    <label for="categoriaFornecedorConfianca" class="text-primary">Categoria</label>
                    <select id="categoriaFornecedorConfianca" name="categoriaFornecedorConfianca" class="field-input">
                        <option value="">Sem categoria</option>
                        @foreach (\App\Enums\CategoriaFornecedor::cases() as $categoria)
                            <option value="{{ $categoria->value }}"
                                @selected(old('categoriaFornecedorConfianca') === $categoria->value)>
                                {{ $categoria->value }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <x-input label="Telefone" name="telefoneFornecedorConfianca" x-data x-mask="(99) 99999-9999"
                    inputmode="numeric" placeholder="(11) 94002-8922" />

                <x-input label="Instagram" name="instagramFornecedorConfianca" placeholder="@usuario" />

                <div class="flex gap-3 mt-6">
                    <x-button type="button" variant="outline" class="flex-1"
                        @click="modalCadastro = false">Cancelar</x-button>
                    <x-button type="submit" class="flex-1">Salvar</x-button>
                </div>
            </form>
        </x-modal>

        @foreach ($fornecedores as $fornecedor)
            <x-fornecedor-confianca-editar-modal :fornecedor="$fornecedor" />
        @endforeach
    </main>

@endsection