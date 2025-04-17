<x-guest-layout>
    <!-- Wrapper utama untuk mengatur layout -->
    <div class="min-h-screen flex items-center justify-center bg-gray-50 dark:bg-gray-900 py-12 px-4 sm:px-6 lg:px-8">
        <!-- Container untuk form login -->
        <div class="max-w-md w-full space-y-8">
            <!-- Logo dan judul aplikasi -->
            <div class="text-center">
                <img class="mx-auto h-24 w-auto" src="{{ asset('storage/images/logo-app.png') }}" alt="Logo Aplikasi">
                <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900 dark:text-white">
                    Selamat Datang
                </h2>
                <p class="mt-2 text-center text-sm text-gray-600 dark:text-gray-400">
                    Silakan masuk ke akun Anda
                </p>
            </div>

            <!-- Session Status untuk menampilkan pesan sukses -->
            <x-auth-session-status class="mb-4" :status="session('status')" />

            <!-- Form Login -->
            <form class="mt-8 space-y-6" method="POST" action="{{ route('login') }}">
                @csrf

                <div class="rounded-md shadow-sm -space-y-px">
                    <!-- Email Address atau Username -->
                    <div>
                        <label for="id_user" class="sr-only">{{ __('Email atau Kode Pengguna') }}</label>
                        <input id="id_user" name="id_user" type="text" required
                            class="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-t-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm"
                            placeholder="Email atau Kode Pengguna" value="{{ old('id_user') }}" autofocus
                            autocomplete="id_user">
                    </div>

                    <!-- Password -->
                    <div>
                        <label for="password" class="sr-only">{{ __('Password') }}</label>
                        <input id="password" name="password" type="password" required
                            class="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-b-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm"
                            placeholder="Password" autocomplete="current-password">
                    </div>
                </div>

                <!-- Error Messages -->
                @if ($errors->any())
                    <div class="rounded-md bg-red-50 p-4">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <!-- Icon Error -->
                                <svg class="h-5 w-5 text-red-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                    fill="currentColor" aria-hidden="true">
                                    <path fill-rule="evenodd"
                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                        clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-red-800">
                                    {{ __('Ups! Terjadi kesalahan.') }}
                                </h3>
                                <div class="mt-2 text-sm text-red-700">
                                    <ul class="list-disc pl-5 space-y-1">
                                        @foreach ($errors->all() as $error)
                                            <li>
                                                @if (str_contains($error, 'validation.required'))
                                                    {{ 'Email atau Kode dan password wajib diisi.' }}
                                                @elseif ($error == 'auth.failed')
                                                    {{ 'Email atau Kode atau password yang Anda masukkan salah.' }}
                                                @elseif ($error == 'auth.throttle')
                                                    {{ 'Terlalu banyak percobaan login yang gagal. Silakan coba lagi setelah beberapa saat.' }}
                                                @else
                                                    {{ $error }}
                                                @endif
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Lupa Password Link -->
                <div class="flex items-center justify-between">
                    @if (Route::has('password.request'))
                        <div class="text-sm">
                            <a href="{{ route('password.request') }}"
                                class="font-medium text-indigo-600 hover:text-indigo-500">
                                {{ __('Lupa kata sandi Anda?') }}
                            </a>
                        </div>
                    @endif
                </div>

                <!-- Submit Button -->
                <div>
                    <button type="submit"
                        class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-150 ease-in-out">
                        <span class="absolute left-0 inset-y-0 flex items-center pl-3">
                            <!-- Icon Lock -->
                            <svg class="h-5 w-5 text-indigo-500 group-hover:text-indigo-400"
                                xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
                                aria-hidden="true">
                                <path fill-rule="evenodd"
                                    d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z"
                                    clip-rule="evenodd" />
                            </svg>
                        </span>
                        {{ __('Masuk') }}
                    </button>
                </div>
            </form>

            <!-- Footer -->
            <div class="text-center text-sm text-gray-600 dark:text-gray-400">
                <p>© {{ date('Y') }} Hadziq.</p>
            </div>
        </div>
    </div>
</x-guest-layout>
