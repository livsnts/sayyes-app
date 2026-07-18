@props(['usuario'])

@php
    $icone = match(true) {
        $usuario->tipoUsuario === 'NOIVO' && $usuario->sexoUsuario === 'F' => 'icone-noiva.png',
        $usuario->tipoUsuario === 'NOIVO' => 'icone-noivo.png',
        default => 'icone-assessor.png',
    };

    $label = match(true) {
        $usuario->tipoUsuario === 'NOIVO' && $usuario->sexoUsuario === 'F' => 'Noiva',
        $usuario->tipoUsuario === 'NOIVO' => 'Noivo',
        default => 'Assessor',
    };
@endphp

<img
    src="{{ asset('images/tipos-usuario/' . $icone) }}"
    alt="{{ $label }}"
    title="{{ $label }}"
    class="w-12 h-12 object-contain"
>