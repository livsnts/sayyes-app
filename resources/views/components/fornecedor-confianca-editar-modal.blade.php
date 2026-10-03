@props(['fornecedor'])

<x-modal show="modalEditar === {{ $fornecedor->id }}" onClose="modalEditar = null">
    <h2 class="titulo text-center mb-4">Editar fornecedor</h2>

    <form method="POST" action="{{ route('fornecedor-confianca.update', $fornecedor) }}">
        @csrf
        @method('PUT')

        <x-input label="Nome" name="nomeFornecedorConfianca" :value="$fornecedor->nomeFornecedorConfianca" required />

        <div class="flex flex-col gap-2 mt-4">
            <label for="categoriaFornecedorConfianca-{{ $fornecedor->id }}" class="text-primary">Categoria</label>
            <select id="categoriaFornecedorConfianca-{{ $fornecedor->id }}" name="categoriaFornecedorConfianca"
                class="field-input">
                <option value="">Sem categoria</option>
                @foreach (\App\Enums\CategoriaFornecedor::cases() as $categoria)
                    <option value="{{ $categoria->value }}"
                        @selected($fornecedor->categoriaFornecedorConfianca?->value === $categoria->value)>
                        {{ $categoria->value }}
                    </option>
                @endforeach
            </select>
        </div>

        <x-input label="Telefone" name="telefoneFornecedorConfianca" :value="$fornecedor->telefoneFornecedorConfianca"
            x-data x-mask="(99) 99999-9999" inputmode="numeric" />

        <x-input label="Instagram" name="instagramFornecedorConfianca" :value="$fornecedor->instagramFornecedorConfianca" />

        <div class="flex gap-3 mt-6">
            <x-button type="button" variant="outline" class="flex-1" @click="modalEditar = null">Cancelar</x-button>
            <x-button type="submit" class="flex-1">Salvar alterações</x-button>
        </div>
    </form>
</x-modal>