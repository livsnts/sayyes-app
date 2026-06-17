<nav class="w-full px-8 py-4">

    <div class="flex items-center justify-between text-primary">

        <a href="/" class="">
            <img src="{{ asset('images/logo-sayyes.png') }}" alt="Say Yes" class="h-16">
        </a>

        @guest

            <x-navbar.menus.guest />

        @else

            @if(auth()->user()->tipoUsuario === 'NOIVO')
                <x-navbar.menus.casal />
            @endif

            @if(auth()->user()->tipoUsuario === 'ASSESSOR')
                <x-navbar.menus.assessor />
            @endif

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit">Sair</button>
            </form>
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
            <h1>{{ auth()->user()->name }}</h1>
            <x-navbar.menus.navbar-user :user="auth()->user()" />

        @endguest

    </div>

</nav>