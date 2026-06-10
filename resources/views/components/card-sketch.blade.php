<div {{
    $attributes->merge([
        'class' => '
                border-3
                border-primary
                bg-background
                p-8
            '
    ])
    }} style="
        border-radius:
        28px 22px 32px 18px /
        18px 30px 20px 28px;
    ">
    {{ $slot }}
</div>