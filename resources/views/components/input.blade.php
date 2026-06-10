@props([
    'label' => null,
    'name',
    'type' => 'text'
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

    <input
        id="{{ $name }}"
        name="{{ $name }}"
        type="{{ $type }}"
        value="{{ old($name) }}"
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