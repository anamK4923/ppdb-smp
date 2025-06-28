<x-guest-layout title="PPDB - SMP Al-Irsyad | Login" class="bg-gray-100 dark:bg-gray-900">
    <div class="text-center mb-6">
        <img src="{{ asset('images/logo.png') }}" class="w-16 h-16 mx-auto mb-2" alt="Logo">
        <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Selamat datang kembali! 👋</h1>
        <p class="text-gray-600 dark:text-gray-400 text-sm mt-1">Silahkan login ke akun anda untuk masuk ke PPDB SMP Al-Irysad</p>
    </div>

    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email -->
        <div>
            <input id="email" name="email" type="text" :value="old('email')" required autofocus autocomplete="email"
                placeholder="Email atau Username"
                class="w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-white px-4 py-2 mt-2 focus:ring-2 focus:ring-indigo-500 focus:outline-none" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4 relative">
            <input id="password" name="password" type="password" required autocomplete="current-password"
                placeholder="Password"
                class="w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-white px-4 py-2 focus:ring-2 focus:ring-indigo-500 focus:outline-none" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me & Forgot Password -->
        <div class="flex items-center justify-between mt-4">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" name="remember"
                    class="rounded border-gray-300 dark:border-gray-600 text-[#009257] shadow-sm focus:ring-indigo-500 dark:focus:ring-indigo-600 dark:focus:ring-offset-gray-800">
                <span class="ml-2 text-sm text-gray-600 dark:text-gray-400">Ingat Saya</span>
            </label>

            @if (Route::has('password.request'))
            <a href="{{ route('password.request') }}"
                class="text-sm text-[#009257] dark:text-indigo-400 hover:underline">Lupa Password?</a>
            @endif
        </div>

        <!-- Login Button -->
        <div class="mt-6">
            <button type="submit"
                class="w-full text-white font-semibold py-2 rounded-lg focus:outline-none focus:ring-2"
                style="background-color: #00ad63;"
                onmouseover="this.style.backgroundColor='#009257'"
                onmouseout="this.style.backgroundColor='#00ad63'">
                Login
            </button>
        </div>

    </form>

    <!-- Register -->
    <div class="text-center mt-6 text-sm text-gray-600 dark:text-gray-400">
        Belum punya akun?
        <a href="{{ route('register') }}" class="text-[#009257] dark:text-indigo-400 hover:underline">Buat akun anda</a>
    </div>
</x-guest-layout>