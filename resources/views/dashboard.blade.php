<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
                <a href="{{ route('mesas.index') }}" class="block rounded-lg border dark:border-gray-700 p-6 bg-white dark:bg-gray-800 hover:ring">
                    <div class="text-lg font-semibold mb-1">Mesas</div>
                    <div class="text-sm text-gray-500 dark:text-gray-400">Gerenciar mesas</div>
                </a>
                <a href="{{ route('categorias.index') }}" class="block rounded-lg border dark:border-gray-700 p-6 bg-white dark:bg-gray-800 hover:ring">
                    <div class="text-lg font-semibold mb-1">Categorias</div>
                    <div class="text-sm text-gray-500 dark:text-gray-400">Bebida, Comida...</div>
                </a>
                <a href="{{ route('produtos.index') }}" class="block rounded-lg border dark:border-gray-700 p-6 bg-white dark:bg-gray-800 hover:ring">
                    <div class="text-lg font-semibold mb-1">Produtos</div>
                    <div class="text-sm text-gray-500 dark:text-gray-400">Cadastro de itens</div>
                </a>
                <a href="{{ route('adicionais.index') }}" class="block rounded-lg border dark:border-gray-700 p-6 bg-white dark:bg-gray-800 hover:ring">
                    <div class="text-lg font-semibold mb-1">Adicionais</div>
                    <div class="text-sm text-gray-500 dark:text-gray-400">Itens extras</div>
                </a>
                <a href="{{ route('usuarios.index') }}" class="block rounded-lg border dark:border-gray-700 p-6 bg-white dark:bg-gray-800 hover:ring">
                    <div class="text-lg font-semibold mb-1">Usuários</div>
                    <div class="text-sm text-gray-500 dark:text-gray-400">Operadores do sistema</div>
                </a>
                <a href="{{ route('endereco.edit') }}" class="block rounded-lg border dark:border-gray-700 p-6 bg-white dark:bg-gray-800 hover:ring">
                    <div class="text-lg font-semibold mb-1">Endereço</div>
                    <div class="text-sm text-gray-500 dark:text-gray-400">Dados do restaurante</div>
                </a>
            </div>
        </div>
    </div>
</x-app-layout>
