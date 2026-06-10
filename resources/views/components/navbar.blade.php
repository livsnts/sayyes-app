<nav class="w-full px-8 py-4">

    <div class="flex items-center justify-between text-primary">

        <a href="/inicio" class="">
            <img src="{{ asset('images/logo-sayyes.png') }}" alt="Say Yes" class="h-16">
        </a>

        @guest

            <x-navbar.menus.guest />

        @else

            @if(auth()->user()->perfil === 'casal')
                <x-navbar.menus.casal />
            @endif

            @if(auth()->user()->perfil === 'assessor')
                <x-navbar.menus.assessor />
            @endif

        @endguest


        @guest

            <div class="flex items-center gap-3">

                <a href="/login">
                    <x-button variant="outline" class="mt-0">
                        Login
                    </x-button>
                </a>


                <a href="/cadastro">
                    <x-button variant="outline" class="mt-0">
                        Cadastre-se
                    </x-button>
                </a>

            </div>

        @else

            <x-navbar.menus.navbar-user :user="auth()->user()" />

        @endguest

    </div>

</nav>