@props([
    'label' => null,
    'name',
    'rows' => 3,
    'placeholder' => '',
    'value' => null,
])

<div class="flex flex-col gap-2 mt-4">
    @if ($label)
        <label for="{{ $name }}" class="text-primary">{{ $label }}</label>
    @endif

    <textarea
        id="{{ $name }}"
        name="{{ $name }}"
        rows="{{ $rows }}"
        placeholder="{{ $placeholder }}"
        {{ $attributes->merge(['class' => 'field-input resize-none']) }}
    >{{ old($name, $value) }}</textarea>

    @error($name)
        <span class="text-danger text-sm">{{ $message }}</span>
    @enderror
</div>
