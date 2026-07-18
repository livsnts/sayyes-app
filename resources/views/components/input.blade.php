@props([
    'label' => null,
    'name',
    'type' => 'text',
    'value' => null,
])

<div class="flex flex-col gap-2">

    @if($label)
        <label
            for="{{ $name }}"
            class="
                text-primary
                pt-2
                mb-0
            "
        >
            {{ $label }}
        </label>
    @endif

    <div class="relative">

        <input
            id="{{ $name }}"
            name="{{ $name }}"
            type="{{ $type }}"
            @if($type !== 'password')
             value="{{ old($name, $value) }}"
            @endif
            {{
                $attributes->merge([
                    'class' => '
                        w-full
                        mt-0
                        mb-1
                        px-4
                        py-3
                        rounded-lg
                        border-2
                        border-primary
                        bg-transparent
                        outline-none
                        transition-all
                        duration-200

                        focus:ring-2
                        focus:ring-primary/20
                        focus:border-primary

                        disabled:bg-gray-100
                        disabled:cursor-not-allowed
                    '
                ])
            }}
        >

        @if($type === 'password')
            <button
                type="button"
                class="
                    absolute
                    right-4
                    top-1/2
                    -translate-y-1/2
                    text-primary
                    cursor-pointer
                "
                onclick="togglePassword('{{ $name }}', this)"
            >
                <i class="fa-regular fa-eye" style="color: #19539d;"></i>
            </button>
        @endif

    </div>

    @error($name)
        <span
            class="
                text-red-600
                text-sm
            "
        >
            {{ $message }}
        </span>
    @enderror

</div>