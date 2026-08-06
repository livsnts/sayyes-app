<nav class="w-full px-4 md:px-8 py-4" x-data="{ open: false }">

    <div class="flex items-center justify-between text-primary">

        <a href="/">
            <img src="{{ asset('images/logo-sayyes.png') }}" alt="Say Yes" class="h-12 md:h-16">
        </a>

        {{-- Links e botões: visíveis a partir do md, escondidos no mobile --}}
        <div class="hidden md:flex items-center gap-8">
            @auth
                @if(auth()->user()->tipoUsuario === 'NOIVO')
                    <x-navbar.menus.casal />
                @else
                    <x-navbar.menus.assessor />
                @endif
            @endauth

            @guest
                <x-navbar.menus.guest />
            @endguest
        </div>

        <div class="hidden md:flex items-center gap-3">
            @guest
                <a href="/login">
                    <x-button variant="outline" class="mt-0">Login</x-button>
                </a>
                <a href="/cadastro">
                    <x-button variant="outline" class="mt-0">Cadastre-se</x-button>
                </a>
            @endguest

            @auth
                <x-navbar.menus.navbar-user :user="auth()->user()" />
            @endauth
        </div>

        {{-- Botão hamburguer: só aparece abaixo do md --}}
        <button @click="open = !open" class="md:hidden text-primary text-2xl w-8" aria-label="Abrir menu">
            <i class="fa-solid fa-bars" x-show="!open"></i>
            <i class="fa-solid fa-xmark" x-show="open" x-cloak></i>
        </button>

    </div>

     <div x-show="open" x-cloak x-transition class="md:hidden flex flex-col gap-4 mt-4 pt-4 border-t border-primary/20">
        @auth
            <a href="{{ route('perfil.edit') }}" class="nav-link">Perfil</a>

            @if(auth()->user()->tipoUsuario === 'NOIVO')
                <x-navbar.menus.casal />
            @else
                <x-navbar.menus.assessor />
            @endif
        @endauth

        @guest
            <x-navbar.menus.guest />

            <div class="flex flex-col gap-3">
                <a href="/login">
                    <x-button variant="outline" class="mt-0 w-full">Login</x-button>
                </a>
                <a href="/cadastro">
                    <x-button variant="outline" class="mt-0 w-full">Cadastre-se</x-button>
                </a>
            </div>
        @endguest
    </div>

</nav>