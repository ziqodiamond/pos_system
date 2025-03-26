<nav x-data="{ open: false, sidebarOpen: false, darkMode: localStorage.getItem('theme') === 'dark' }" x-init="$watch('darkMode', val => {
    document.documentElement.classList.toggle('dark', val);
    localStorage.setItem('theme', val ? 'dark' : 'light');
})"
    class="bg-white border-b border-gray-100 dark:bg-gray-900 dark:border-gray-700">

    <div class="flex items-center justify-between h-16 px-4">
        <!-- Hamburger Menu Button -->
        <button @click="sidebarOpen = !sidebarOpen" type="button"
            class="inline-flex items-center p-2 text-sm text-gray-500 rounded-lg hover:bg-gray-100 focus:outline-none dark:text-gray-400 dark:hover:bg-gray-700 transition-transform duration-300">
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
                <x-application-logo class="h-9 w-auto" />
            </a>
        </div>

        <!-- Notification & Dark Mode Button -->
        <div class="flex items-center space-x-4">
            <button @click="darkMode = !darkMode"
                class="p-2 rounded-lg bg-gray-200 dark:bg-gray-700 transition-transform duration-300 ease-in-out">
                <span x-text="darkMode ? '☀️' : '🌙'">🌙</span>
            </button>
            <button type="button" class="relative text-gray-500 hover:text-gray-700 focus:outline-none">
                <span class="sr-only">View notifications</span>
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V4a2 2 0 10-4 0v1.341C7.67 7.165 7 9.388 7 11v3.159c0 .538-.214 1.055-.595 1.436L5 17h5m5 0a3 3 0 11-6 0m6 0H9">
                    </path>
                </svg>
            </button>
        </div>
    </div>

    <!-- Overlay (klik di luar sidebar) -->
    <div x-show="sidebarOpen" @click="sidebarOpen = false"
        class="fixed inset-0 bg-black bg-opacity-50 z-40 transition-opacity duration-300" x-transition.opacity x-cloak>
    </div>

    <!-- Sidebar -->
    <aside x-show="sidebarOpen" class="fixed top-0 left-0 w-72 h-screen bg-gray-50 dark:bg-gray-800 shadow-lg z-50"
        x-transition:enter="transition-transform duration-300" x-transition:enter-start="-translate-x-full"
        x-transition:enter-end="translate-x-0" x-transition:leave="transition-transform duration-300" x-cloak
        x-transition:leave-start="translate-x-0" x-transition:leave-end="-translate-x-full" x-cloak>
        <div class="p-4 flex justify-center items-center bg-gray-100 dark:bg-gray-900">
            <x-application-logo class="h-24 w-auto" />
        </div>
        <ul class="p-4 space-y-2">
            <li>
                <a href="{{ route('dashboard') }}" class="block p-2 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-700"
                    :class="{
                        'bg-gray-300 dark:bg-gray-600': '{{ Request::route()->getName() }}'
                        === 'dashboard'
                    }">
                    Dashboard
                </a>
            </li>
            <li>
                <a href="{{ route('master-data.index') }}" wire:navigate
                    class="block p-2 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-700"
                    :class="{
                        'bg-gray-300 dark:bg-gray-600': '{{ Request::route()->getName() }}'
                        === 'master-data.index'
                    }">
                    Master Data
                </a>
            </li>
            <li>
                <a href="{{ route('pembelian.index') }}" wire:navigate
                    class="block p-2 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-700"
                    :class="{
                        'bg-gray-300 dark:bg-gray-600': '{{ Request::route()->getName() }}'
                        === 'pembelian.index'
                    }">
                    Pembelian
                </a>
            </li>
            <li>
                <a href="{{ route('penjualan.index') }}" wire:navigate
                    class="block p-2 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-700"
                    :class="{
                        'bg-gray-300 dark:bg-gray-600': '{{ Request::route()->getName() }}'
                        === 'penjualan.index'
                    }">
                    Penjualan
                </a>
            </li>
            <li>
                <a href="{{ route('penjualan.index') }}" wire:navigate
                    class="block p-2 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-700"
                    :class="{
                        'bg-gray-300 dark:bg-gray-600': '{{ Request::route()->getName() }}'
                        === 'penjualan.index'
                    }">
                    Inventori
                </a>
            </li>
            <li>
                <a href="{{ route('penjualan.index') }}" wire:navigate
                    class="block p-2 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-700"
                    :class="{
                        'bg-gray-300 dark:bg-gray-600': '{{ Request::route()->getName() }}'
                        === 'penjualan.index'
                    }">
                    Laporan
                </a>
            </li>
            <li>
                <a href="{{ route('penjualan.index') }}" wire:navigate
                    class="block p-2 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-700"
                    :class="{
                        'bg-gray-300 dark:bg-gray-600': '{{ Request::route()->getName() }}'
                        === 'penjualan.index'
                    }">
                    Pengaturan
                </a>
            </li>
        </ul>
        <div class="p-4 flex justify-between items-center bg-gray-100 dark:bg-gray-900">
            <div class="flex items-center space-x-3">
                <img class="w-10 h-10 rounded-full" src="{{ Auth::user()->profile_photo_url }}" alt="User Profile">
                <div>
                    <p class="text-sm font-bold">{{ Auth::user()->name }}</p>
                    <p class="text-xs text-gray-500">{{ Auth::user()->username }}</p>
                </div>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                    class="p-2 text-red-600 dark:text-red-400 hover:text-red-800 dark:hover:text-red-500 transition-transform duration-300 ease-in-out">Logout</button>
            </form>
        </div>
    </aside>
</nav>
