@php

$roleLabel = match($user->perfil) {

    'assessor' => 'ASSESSOR',

    'casal' =>
        $user->sexo === 'F'
        ? 'NOIVA'
        : 'NOIVO',

    default => ''
};

$icon = match($user->perfil) {

    'assessor' => 'phone.svg',

    'casal' =>
        $user->sexo === 'F'
        ? 'rings-female.svg'
        : 'rings-male.svg',
};

@endphp