<nav x-data="{ open: false, sidebarOpen: false, darkMode: localStorage.getItem('theme') === 'dark' }" x-init="$watch('darkMode', val => {
    document.documentElement.classList.toggle('dark', val);
    localStorage.setItem('theme', val ? 'dark' : 'light');
})"
    class="bg-gradient-to-r from-blue-700 via-indigo-700 to-blue-800 dark:from-blue-800 dark:via-indigo-900 dark:to-blue-900 border-b border-gray-100 dark:border-gray-700">

    <div class="flex items-center justify-between h-16 px-4">
        <!-- Hamburger Menu Button -->
        <button @click="sidebarOpen = !sidebarOpen" type="button"
            class="inline-flex items-center p-2 text-sm text-gray-100 rounded-lg hover:bg-gray-100 focus:outline-none dark:text-gray-400 dark:hover:bg-gray-700 transition-transform duration-300">
            <span class="sr-only">Open sidebar</span>
            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                <path clip-rule="evenodd" fill-rule="evenodd"
                    d="M2 4.75A.75.75 0 012.75 4h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 4.75zm0 10.5a.75.75 0 01.75-.75h7.5a.75.75 0 010 1.5h-7.5a.75.75 0 01-.75-.75zM2 10a.75.75 0 01.75-.75h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 10z">
                </path>
            </svg>
        </button>

        <!-- Application Logo -->
        <div class="mx-auto">
            <a href="{{ route('dashboard') }}">
                <x-application-logo class="h-14 w-auto" />
            </a>
        </div>

        <!-- Notification & Dark Mode Button -->
        <div class="flex items-center space-x-4">
            <button @click="darkMode = !darkMode"
                class="p-2 rounded-lg bg-gray-200 dark:bg-gray-700 transition-transform duration-300 ease-in-out">
                <span x-text="darkMode ? '☀️' : '🌙'">🌙</span>
            </button>

        </div>
    </div>

    <!-- Overlay (klik di luar sidebar) -->
    <div x-show="sidebarOpen" @click="sidebarOpen = false"
        class="fixed inset-0 bg-black bg-opacity-50 z-40 transition-opacity duration-300" x-transition.opacity x-cloak>
    </div>

    <!-- Sidebar -->
    <aside x-show="sidebarOpen"
        class="fixed top-0 left-0 w-72 h-screen bg-gray-50 dark:bg-gray-800 shadow-lg z-50 flex flex-col justify-between"
        x-transition:enter="transition-transform duration-300" x-transition:enter-start="-translate-x-full"
        x-transition:enter-end="translate-x-0" x-transition:leave="transition-transform duration-300"
        x-transition:leave-start="translate-x-0" x-transition:leave-end="-translate-x-full" x-cloak>

        <!-- Header Logo -->
        <div
            class="p-2 flex justify-center items-center bg-gradient-to-r from-blue-700 via-indigo-700 to-blue-800 dark:from-blue-800 dark:via-indigo-900 dark:to-blue-900 border-b border-gray-200 dark:border-gray-700">
            <x-application-logo class="h-32 w-auto" />
        </div>

        <!-- Menu Navigation -->
        <nav class="flex-grow overflow-y-auto">
            <ul class="p-4 space-y-1">
                <li>
                    <a href="{{ route('dashboard') }}"
                        class="flex items-center p-3 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-700 transition-all duration-200"
                        :class="{
                            'bg-blue-100 text-blue-700 dark:bg-gray-600 dark:text-blue-300': '{{ Request::route()->getName() }}'
                            === 'dashboard'
                        }">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                        </svg>
                        <span class="font-medium">Dashboard</span>
                    </a>
                </li>
                @if (Auth::user()->role === 'admin' || Auth::user()->role === 'super_admin')
                    <li>
                        <a href="{{ route('master-data.index') }}" wire:navigate
                            class="flex items-center p-3 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-700 transition-all duration-200"
                            :class="{
                                'bg-blue-100 text-blue-700 dark:bg-gray-600 dark:text-blue-300': '{{ Request::route()->getName() }}'
                                === 'master-data.index'
                            }">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4" />
                            </svg>
                            <span class="font-medium">Master Data</span>
                        </a>
                    </li>
                @endif
                @if (Auth::user()->role === 'admin' || Auth::user()->role === 'super_admin' || Auth::user()->role === 'gudang')
                    <li>
                        <a href="{{ route('pembelian.index') }}" wire:navigate
                            class="flex items-center p-3 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-700 transition-all duration-200"
                            :class="{
                                'bg-blue-100 text-blue-700 dark:bg-gray-600 dark:text-blue-300': '{{ Request::route()->getName() }}'
                                === 'pembelian.index'
                            }">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                            </svg>
                            <span class="font-medium">Pembelian</span>
                        </a>
                    </li>
                @endif
                @if (Auth::user()->role === 'admin' || Auth::user()->role === 'super_admin' || Auth::user()->role === 'kasir')
                    <li>
                        <a href="{{ route('penjualan.index') }}" wire:navigate
                            class="flex items-center p-3 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-700 transition-all duration-200"
                            :class="{
                                'bg-blue-100 text-blue-700 dark:bg-gray-600 dark:text-blue-300': '{{ Request::route()->getName() }}'
                                === 'penjualan.index'
                            }">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                            <span class="font-medium">Penjualan</span>
                        </a>
                    </li>
                @endif
                @if (Auth::user()->role === 'admin' || Auth::user()->role === 'super_admin' || Auth::user()->role === 'gudang')
                    <li>
                        <a href="{{ route('inventori.index') }}" wire:navigate
                            class="flex items-center p-3 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-700 transition-all duration-200"
                            :class="{
                                'bg-blue-100 text-blue-700 dark:bg-gray-600 dark:text-blue-300': '{{ Request::route()->getName() }}'
                                === 'inventori.index'
                            }">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                            </svg>
                            <span class="font-medium">Inventori</span>
                        </a>
                    </li>
                @endif
                @if (Auth::user()->role === 'admin' || Auth::user()->role === 'super_admin')
                    <li>
                        <a href="{{ route('laporan.index') }}" wire:navigate
                            class="flex items-center p-3 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-700 transition-all duration-200"
                            :class="{
                                'bg-blue-100 text-blue-700 dark:bg-gray-600 dark:text-blue-300': '{{ Request::route()->getName() }}'
                                === 'laporan.index'
                            }">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            <span class="font-medium">Laporan</span>
                        </a>
                    </li>
                @endif
                @if (Auth::user()->role === 'super_admin')
                    <li>
                        <a href="{{ route('settings.index') }}" wire:navigate
                            class="flex items-center p-3 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-700 transition-all duration-200"
                            :class="{
                                'bg-blue-100 text-blue-700 dark:bg-gray-600 dark:text-blue-300': '{{ Request::route()->getName() }}'
                                === 'settings.index'
                            }">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            <span class="font-medium">Pengaturan</span>
                        </a>
                    </li>
                @endif
            </ul>
        </nav>

        <!-- Footer with user info, copyright and logout -->
        <div class="p-4 bg-gray-100 dark:bg-gray-900 border-t border-gray-200 dark:border-gray-700">
            <!-- User info and logout button -->
            <div class="flex justify-between items-center mb-3">
                <div>
                    <p class="text-sm font-bold">{{ Auth::user()->name }}</p>
                    <p class="text-xs text-gray-500 dark:text-gray-400">{{ Auth::user()->username }}</p>
                </div>

                <!-- Logout Icon Button -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                        class="p-2 text-red-600 dark:text-red-400 hover:text-red-800 dark:hover:text-red-500 transition-colors duration-200 rounded-full hover:bg-gray-200 dark:hover:bg-gray-700">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>
                    </button>
                </form>
            </div>

            <!-- Copyright -->
            <div class="text-xs text-center text-gray-500 dark:text-gray-400">
                &copy; {{ date('Y') }} Hadziq
            </div>
        </div>
    </aside>
    <!-- End Sidebar -->
</nav>
