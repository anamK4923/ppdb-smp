<x-guestRegister-layout>
    <div class="text-center mb-6">
        <a href="{{ url('/') }}">
            <img src="{{ asset('images/logo.png') }}" class="w-16 h-16 mx-auto mb-2" alt="Logo">
        </a>
        <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Registrasi Akun</h1>
    </div>

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <!-- Nama Lengkap -->
            <div>
                <x-input-label for="name" :value="'Nama Lengkap'" />
                <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
                <x-input-error :messages="$errors->get('name')" class="mt-2" />
            </div>

            <!-- NISN -->
            <div>
                <x-input-label for="nisn" :value="'NISN'" />
                <x-text-input id="nisn" class="block mt-1 w-full" type="text" name="nisn" :value="old('nisn')" required autocomplete="nisn" />
                <x-input-error :messages="$errors->get('nisn')" class="mt-2" />
            </div>

            <!-- Email -->
            <div>
                <x-input-label for="email" :value="'Email'" />
                <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <!-- Username -->
            <div>
                <x-input-label for="username" :value="'Username'" />
                <x-text-input id="username" class="block mt-1 w-full" type="text" name="username" :value="old('username')" required autocomplete="username" />
                <x-input-error :messages="$errors->get('username')" class="mt-2" />
            </div>

            <!-- Password -->
            <div>
                <x-input-label for="password" :value="'Kata Sandi'" />
                <x-text-input id="password" class="block mt-1 w-full"
                    type="password"
                    name="password"
                    required autocomplete="new-password" />
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <!-- Konfirmasi Password -->
            <div>
                <x-input-label for="password_confirmation" :value="'Konfirmasi Kata Sandi'" />
                <x-text-input id="password_confirmation" class="block mt-1 w-full"
                    type="password"
                    name="password_confirmation" required autocomplete="new-password" />
                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
            </div>
        </div>

        <!-- Aksi -->
        <div class="flex items-center justify-between mt-6">
            <a class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800" href="{{ route('login') }}">
                Sudah punya akun?
            </a>

            <x-primary-button class="ms-4" style="background-color: #00ad63;"
                onmouseover="this.style.backgroundColor='#009257'"
                onmouseout="this.style.backgroundColor='#00ad63'">
                Daftar
            </x-primary-button>
        </div>
    </form>

</x-guestRegister-layout>