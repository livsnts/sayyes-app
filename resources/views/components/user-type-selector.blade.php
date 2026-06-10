@props([
    'name' => 'tipo_usuario'
])

<div class="mt-6 mb-6">

    <label class="text-primary">
        Tipo do usuário
    </label>

    <div class="flex gap-4 mt-2 justify-center">

        <label class="user-type-card">

            <input
                type="radio"
                name="{{ $name }}"
                value="noiva"
                class="hidden peer"
                {{ old($name) === 'noiva' ? 'checked' : '' }}
            >

            <img
                class="h-18"
                src="{{ asset('images/tipos-usuario/vestido.png') }}"
                alt="Noiva"
            >

            <span>Noiva</span>

        </label>

        <label class="user-type-card">

            <input
                type="radio"
                name="{{ $name }}"
                value="noivo"
                class="hidden peer"
                {{ old($name) === 'noivo' ? 'checked' : '' }}
            >

            <img
                class="h-18"
                src="{{ asset('images/tipos-usuario/terno.png') }}"
                alt="Noivo"
            >

            <span>Noivo</span>

        </label>

        <label class="user-type-card">

            <input
                type="radio"
                name="{{ $name }}"
                value="assessor"
                class="hidden peer"
                {{ old($name) === 'assessor' ? 'checked' : '' }}
            >

            <img
                class="h-18"
                src="{{ asset('images/tipos-usuario/prancheta.png') }}"
                alt="Assessor"
            >

            <span>Assessor</span>

        </label>

    </div>

    @error($name)
        <p class="text-red-500 text-sm mt-2 text-center">
            {{ $message }}
        </p>
    @enderror

</div>