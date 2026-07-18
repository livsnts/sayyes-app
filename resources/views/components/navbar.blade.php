<nav class="w-full px-8 py-4">

    <div class="flex items-center justify-between text-primary">

        <a href="/" class="">
            <img src="{{ asset('images/logo-sayyes.png') }}" alt="Say Yes" class="h-16">
        </a>

        @auth
            @if(auth()->user()->tipoUsuario === 'NOIVO')
                <x-navbar.menus.casal />
            @else
                <x-navbar.menus.assessor />
            @endif
        @endauth

        @guest
            <x-navbar.menus.guest />

            <div class="flex items-center gap-3">
                <a href="/login">
                    <x-button variant="outline" class="mt-0">Login</x-button>
                </a>
                <a href="/cadastro">
                    <x-button variant="outline" class="mt-0">Cadastre-se</x-button>
                </a>
            </div>
        @endguest

        @auth
            <x-navbar.menus.navbar-user :user="auth()->user()" />
        @endauth

    </div>

</nav>
