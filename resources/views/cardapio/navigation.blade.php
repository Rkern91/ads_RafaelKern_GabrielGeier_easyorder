<nav x-data="{ open: false }"
     style="background-color: black;"
     class="fixed top-0 left-0 w-full z-50 bg-black
            dark:bg-gray-800 border-b dark:border-gray-700 mb-8">

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">

            <!-- ESQUERDA: Logo + Categorias -->
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('cardapio.index') }}">
                        <x-application-logo class="block h-9 w-auto fill-current text-gray-800 dark:text-gray-200"/>
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    @foreach($categorias as $cat)
                        <x-nav-link
                                :href="route('cardapio.categoria', ['categoria' => $cat->cd_categoria])"
                                :active="$cat->cd_categoria == $id_categoria_ativa"
                        >
                            {{ $cat->nm_categoria }}
                        </x-nav-link>
                    @endforeach
                </div>
            </div>

            <!-- DIREITA: Novos links -->
            <div class="hidden sm:flex items-center space-x-8">
                <x-nav-link href="{{ route('cardapio.revisao') }}" :active="request()->routeIs('cardapio.revisao')">
                    🛒 Carrinho
                </x-nav-link>

                <x-nav-link href="{{ route('cardapio.conta') }}" :active="request()->routeIs('cardapio.conta')">
                    💳 Conta da Mesa
                </x-nav-link>
            </div>

        </div>
    </div>
</nav>

<div class="fixed inset-0 z-10 pointer-events-none"
     style="background-image:url('{{ asset('images/bg-3840x2400.jpg') }}');
                    background-size:cover;
                    background-position:center;
                    background-attachment:fixed;
                    opacity: .1">
    <div class="w-full h-full bg-black/50"></div>
</div>
