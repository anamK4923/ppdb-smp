<x-guest-layout>
    <div class="mb-4 text-sm text-gray-600 dark:text-gray-400">
        {{ __('Lupa kata sandi Anda? Tidak masalah. Silakan masukkan alamat email Anda dan kami akan mengirimkan tautan untuk mereset kata sandi agar Anda bisa membuat yang baru.') }}
    </div>

    <!-- Status Sesi -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <!-- Alamat Email -->
        <div>
            <x-input-label for="email" :value="'Email'" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <x-primary-button>
                Kirim Tautan Reset Kata Sandi
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>