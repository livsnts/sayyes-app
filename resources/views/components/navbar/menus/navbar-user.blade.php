@php
$roleLabel = match(true) {
    $user->tipoUsuario === 'NOIVO' && $user->sexoUsuario === 'F' => 'NOIVA',
    $user->tipoUsuario === 'NOIVO' => 'NOIVO',
    default => 'ASSESSOR',
};

$icon = match(true) {
    $user->tipoUsuario === 'NOIVO' && $user->sexoUsuario === 'F' => 'icone-noiva.png',
    $user->tipoUsuario === 'NOIVO' => 'icone-noivo.png',
    default => 'icone-assessor.png',
};
@endphp

<a href="{{ route('perfil.edit') }}" class="flex items-center gap-2 text-primary hover:opacity-80 transition">
    <div class="text-right leading-tight">
        <p class="font-semibold text-sm">Olá, {{ explode(' ', $user->name)[0] }}!</p>
        <p class="text-xs text-text-muted">{{ $roleLabel }}</p>
    </div>
    <img src="{{ asset('images/tipos-usuario/' . $icon) }}" alt="{{ $roleLabel }}" class="h-10 w-10 object-contain">
</a>
