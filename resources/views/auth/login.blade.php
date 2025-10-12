<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <div>
            <x-input-label for="ds_email" value="E-mail" />
            <x-text-input id="ds_email" name="ds_email" type="email" class="mt-1 block w-full" :value="old('ds_email')" required autofocus />
            <x-input-error :messages="$errors->get('ds_email')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="ds_senha" value="Senha" />
            <x-text-input id="ds_senha" name="ds_senha" type="password" class="mt-1 block w-full" required />
            <x-input-error :messages="$errors->get('ds_senha')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            @if (Route::has('password.request'))
                <a class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('password.request') }}">
                    Esqueceu a senha?
                </a>
            @endif
            <x-primary-button class="ms-4">Entrar</x-primary-button>
        </div>
    </form>

</x-guest-layout>
