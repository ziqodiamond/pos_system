<x-layout>
    <div>
        @include('components.breadcrumbs')
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold">Menu Pembelian</h1>
        </div>

        <div class="grid gap-4 grid-cols-3 xs:grid-cols-3 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6">

            <a href="{{ route('pembelian.create') }}"
                class="flex flex-col items-center justify-center p-4 bg-white rounded-lg shadow hover:bg-gray-50 dark:bg-gray-700 w-28 h-28 xs:w-28 xs:h-28 sm:w-28 sm:h-28 md:w-32 md:h-32 lg:h-36 lg:w-36 xl:h-40 xl:w-40 transition duration-300 ease-in-out transform hover:scale-105">
                {{-- Icon Cart Plus untuk Pembelian Baru --}}
                <svg class="w-[48px] h-[48px] text-gray-800 dark:text-white mb-3" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z">
                    </path>
                </svg>

                <span class="font-semibold text-center text-xs sm:text-xs md:text-sm lg:text-md">Pembelian Baru</span>
            </a>

            <a href="{{ route('daftar-pembelian.index') }}"
                class="flex flex-col items-center justify-center p-4 bg-white rounded-lg shadow hover:bg-gray-50 dark:bg-gray-700 w-28 h-28 xs:w-28 xs:h-28 sm:w-28 sm:h-28 md:w-32 md:h-32 lg:h-36 lg:w-36 xl:h-40 xl:w-40 transition duration-300 ease-in-out transform hover:scale-105">
                {{-- Icon Clipboard List untuk Daftar Pembelian --}}
                <svg class="w-[48px] h-[48px] text-gray-800 dark:text-white mb-3" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01">
                    </path>
                </svg>

                <span class="font-semibold text-center text-xs sm:text-xs md:text-sm lg:text-md">Daftar Pembelian</span>
            </a>

            <a href="{{ route('faktur.index') }}"
                class="flex flex-col items-center justify-center p-4 bg-white rounded-lg shadow hover:bg-gray-50 dark:bg-gray-700 w-28 h-28 xs:w-28 xs:h-28 sm:w-28 sm:h-28 md:w-32 md:h-32 lg:h-36 lg:w-36 xl:h-40 xl:w-40 transition duration-300 ease-in-out transform hover:scale-105">
                {{-- Icon Document Text untuk Faktur Pembelian --}}
                <svg class="w-[48px] h-[48px] text-gray-800 dark:text-white mb-3" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                    </path>
                </svg>

                <span class="font-semibold text-center text-xs sm:text-xs md:text-sm lg:text-md">Faktur Pembelian</span>
            </a>

        </div>
    </div>
</x-layout>
