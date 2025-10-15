<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight" style="text-align: center;">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
                <a href="{{ route('mesas.index') }}" style="background-color: black; margin-bottom: 5px;" class="block rounded-lg border border-white/20 p-6 text-white hover:ring">
                    <div class="flex items-center gap-4">
                        <i class="fa-solid fa-chair text-3xl opacity-70"></i>
                        <div>
                            <div class="text-lg font-semibold mb-1">Mesas</div>
                            <div class="text-sm text-gray-300">Gerenciar mesas</div>
                        </div>
                    </div>
                </a>
                <a href="{{ route('categorias.index') }}" style="background-color: black; margin-bottom: 5px;" class="group block rounded-lg border border-white/20 p-6 text-white hover:ring">
                    <div class="flex items-center gap-4">
                        <i class="fa-solid fa-tags text-3xl opacity-70 group-hover:opacity-100"></i>
                        <div>
                            <div class="text-lg font-semibold mb-1">Categorias</div>
                            <div class="text-sm text-gray-300">Bebida, Comida...</div>
                        </div>
                    </div>
                </a>
                <a href="{{ route('produtos.index') }}" style="background-color: black; margin-bottom: 5px;" class="group block rounded-lg border border-white/20 p-6 text-white hover:ring">
                    <div class="flex items-center gap-4">
                        <i class="fa-solid fa-utensils text-3xl opacity-70 group-hover:opacity-100"></i>
                        <div>
                            <div class="text-lg font-semibold mb-1">Produtos</div>
                            <div class="text-sm text-gray-300">Cadastro de itens</div>
                        </div>
                    </div>
                </a>
                <a href="{{ route('adicionais.index') }}" style="background-color: black; margin-bottom: 5px;" class="group block rounded-lg border border-white/20 p-6 text-white hover:ring">
                    <div class="flex items-center gap-4">
                        <i class="fa-solid fa-plus text-3xl opacity-70 group-hover:opacity-100"></i>
                        <div>
                            <div class="text-lg font-semibold mb-1">Adicionais</div>
                            <div class="text-sm text-gray-300">Itens extras</div>
                        </div>
                    </div>
                </a>
                <a href="{{ route('usuarios.index') }}" style="background-color: black; margin-bottom: 5px;" class="group block rounded-lg border border-white/20 p-6 text-white hover:ring">
                    <div class="flex items-center gap-4">
                        <i class="fa-solid fa-user-gear text-3xl opacity-70 group-hover:opacity-100"></i>
                        <div>
                            <div class="text-lg font-semibold mb-1">Usuários</div>
                            <div class="text-sm text-gray-300">Operadores do sistema</div>
                        </div>
                    </div>
                </a>
                <a href="{{ route('endereco.edit') }}" style="background-color: black; margin-bottom: 5px;" class="group block rounded-lg border border-white/20 p-6 text-white hover:ring">
                    <div class="flex items-center gap-4">
                        <i class="fa-solid fa-location-dot text-3xl opacity-70 group-hover:opacity-100"></i>
                        <div>
                            <div class="text-lg font-semibold mb-1">Endereço</div>
                            <div class="text-sm text-gray-300">Dados do restaurante</div>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>
</x-app-layout>
