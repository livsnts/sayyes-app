@php

$roleLabel = match($user->tipoUsuario) {

    'ASSESSOR' => 'ASSESSOR',
    'NOIVO'    => $user->sexoUsuario === 'F' ? 'NOIVA' : 'NOIVO',
    default => ''
};

$icon = match($user->tipoUsuario) {

    'ASSESSOR' => 'icone-assessor.png',

    'NOIVO' =>
        $user->sexoUsuario === 'F'
        ? 'icone-noiva.png'
        : 'icone-noivo.png',
};

@endphp