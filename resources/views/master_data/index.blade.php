<x-layout>
    <div>
        <livewire:component.breadcrumbs />
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold">Master Data</h1>
        </div>

        <div class="grid gap-4 grid-cols-3 xs:grid-cols-3 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6">

            <a href="{{ route('barang.index') }}"
                class="flex flex-col items-center justify-center p-4 bg-white rounded-lg shadow hover:bg-gray-50 dark:bg-gray-700 w-28 h-28 xs:w-28 xs:h-28 sm:w-28 sm:h-28 md:w-32 md:h-32 lg:h-36 lg:w-36 xl:h-40 xl:w-40 transition duration-300 ease-in-out transform hover:scale-105">
                <svg class="w-[48px] h-[48px] text-gray-800 dark:text-white mb-3" xmlns="http://www.w3.org/2000/svg"
                    width="24" height="24" fill="currentColor" class="bi bi-box-seam-fill" viewBox="0 0 16 16">
                    <path fill-rule="evenodd"
                        d="M15.528 2.973a.75.75 0 0 1 .472.696v8.662a.75.75 0 0 1-.472.696l-7.25 2.9a.75.75 0 0 1-.557 0l-7.25-2.9A.75.75 0 0 1 0 12.331V3.669a.75.75 0 0 1 .471-.696L7.443.184l.01-.003.268-.108a.75.75 0 0 1 .558 0l.269.108.01.003zM10.404 2 4.25 4.461 1.846 3.5 1 3.839v.4l6.5 2.6v7.922l.5.2.5-.2V6.84l6.5-2.6v-.4l-.846-.339L8 5.961 5.596 5l6.154-2.461z" />
                </svg>

                <span class="font-semibold text-center text-xs sm:text-xs md:text-sm lg:text-md">Data Barang</span>
            </a>

            <a href="{{ route('supplier.index') }}"
                class="flex flex-col items-center justify-center p-4 bg-white rounded-lg shadow hover:bg-gray-50 dark:bg-gray-700 w-28 h-28 xs:w-28 xs:h-28 sm:w-28 sm:h-28 md:w-32 md:h-32 lg:h-36 lg:w-36 xl:h-40 xl:w-40 transition duration-300 ease-in-out transform hover:scale-105">
                <svg class="w-[48px] h-[48px] text-gray-800 dark:text-white mb-3" xmlns="http://www.w3.org/2000/svg"
                    xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor"
                    class="bi bi-shop" viewBox="0 0 16 16">
                    <path
                        d="M2.97 1.35A1 1 0 0 1 3.73 1h8.54a1 1 0 0 1 .76.35l2.609 3.044A1.5 1.5 0 0 1 16 5.37v.255a2.375 2.375 0 0 1-4.25 1.458A2.37 2.37 0 0 1 9.875 8 2.37 2.37 0 0 1 8 7.083 2.37 2.37 0 0 1 6.125 8a2.37 2.37 0 0 1-1.875-.917A2.375 2.375 0 0 1 0 5.625V5.37a1.5 1.5 0 0 1 .361-.976zm1.78 4.275a1.375 1.375 0 0 0 2.75 0 .5.5 0 0 1 1 0 1.375 1.375 0 0 0 2.75 0 .5.5 0 0 1 1 0 1.375 1.375 0 1 0 2.75 0V5.37a.5.5 0 0 0-.12-.325L12.27 2H3.73L1.12 5.045A.5.5 0 0 0 1 5.37v.255a1.375 1.375 0 0 0 2.75 0 .5.5 0 0 1 1 0M1.5 8.5A.5.5 0 0 1 2 9v6h1v-5a1 1 0 0 1 1-1h3a1 1 0 0 1 1 1v5h6V9a.5.5 0 0 1 1 0v6h.5a.5.5 0 0 1 0 1H.5a.5.5 0 0 1 0-1H1V9a.5.5 0 0 1 .5-.5M4 15h3v-5H4zm5-5a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v3a1 1 0 0 1-1 1h-2a1 1 0 0 1-1-1zm3 0h-2v3h2z" />
                </svg>

                <span class="font-semibold text-center text-xs sm:text-xs md:text-sm lg:text-md">Data Supplier</span>
            </a>

            <a href="{{ route('customer.index') }}"
                class="flex flex-col items-center justify-center p-4 bg-white rounded-lg shadow hover:bg-gray-50 dark:bg-gray-700 w-28 h-28 xs:w-28 xs:h-28 sm:w-28 sm:h-28 md:w-32 md:h-32 lg:h-36 lg:w-36 xl:h-40 xl:w-40 transition duration-300 ease-in-out transform hover:scale-105">
                <svg class="w-[48px] h-[48px] text-gray-800 dark:text-white mb-3" xmlns="http://www.w3.org/2000/svg"
                    width="24" height="24" fill="currentColor" class="bi bi-people-fill" viewBox="0 0 16 16">
                    <path
                        d="M7 14s-1 0-1-1 1-4 5-4 5 3 5 4-1 1-1 1zm4-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6m-5.784 6A2.24 2.24 0 0 1 5 13c0-1.355.68-2.75 1.936-3.72A6.3 6.3 0 0 0 5 9c-4 0-5 3-5 4s1 1 1 1zM4.5 8a2.5 2.5 0 1 0 0-5 2.5 2.5 0 0 0 0 5" />
                </svg>

                <span class="font-semibold text-center text-xs sm:text-xs md:text-sm lg:text-md">Data Customer</span>
            </a>

            <a href="{{ route('satuan.index') }}"
                class="flex flex-col items-center justify-center p-4 bg-white rounded-lg shadow hover:bg-gray-50 dark:bg-gray-700 w-28 h-28 xs:w-28 xs:h-28 sm:w-28 sm:h-28 md:w-32 md:h-32 lg:h-36 lg:w-36 xl:h-40 xl:w-40 transition duration-300 ease-in-out transform hover:scale-105">
                <svg class="w-[48px] h-[48px] text-gray-800 dark:text-white mb-3" aria-hidden="true"
                    xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor"
                    viewBox="0 0 24 24">
                    <path fill-rule="evenodd"
                        d="M5 8a4 4 0 1 1 7.796 1.263l-2.533 2.534A4 4 0 0 1 5 8Zm4.06 5H7a4 4 0 0 0-4 4v1a2 2 0 0 0 2 2h2.172a2.999 2.999 0 0 1-.114-1.588l.674-3.372a3 3 0 0 1 .82-1.533L9.06 13Zm9.032-5a2.907 2.907 0 0 0-2.056.852L9.967 14.92a1 1 0 0 0-.273.51l-.675 3.373a1 1 0 0 0 1.177 1.177l3.372-.675a1 1 0 0 0 .511-.273l6.07-6.07a2.91 2.91 0 0 0-.944-4.742A2.907 2.907 0 0 0 18.092 8Z"
                        clip-rule="evenodd" />
                </svg>

                <span class="font-semibold text-center text-xs sm:text-xs md:text-sm lg:text-md">Data Satuan</span>
            </a>

            <a href="{{ route('konversi.index') }}"
                class="flex flex-col items-center justify-center p-4 bg-white rounded-lg shadow hover:bg-gray-50 dark:bg-gray-700 w-28 h-28 xs:w-28 xs:h-28 sm:w-28 sm:h-28 md:w-32 md:h-32 lg:h-36 lg:w-36 xl:h-40 xl:w-40 transition duration-300 ease-in-out transform hover:scale-105">
                <svg class="w-[48px] h-[48px] text-gray-800 dark:text-white mb-3" aria-hidden="true"
                    xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor"
                    viewBox="0 0 24 24">
                    <path fill-rule="evenodd"
                        d="M5 8a4 4 0 1 1 7.796 1.263l-2.533 2.534A4 4 0 0 1 5 8Zm4.06 5H7a4 4 0 0 0-4 4v1a2 2 0 0 0 2 2h2.172a2.999 2.999 0 0 1-.114-1.588l.674-3.372a3 3 0 0 1 .82-1.533L9.06 13Zm9.032-5a2.907 2.907 0 0 0-2.056.852L9.967 14.92a1 1 0 0 0-.273.51l-.675 3.373a1 1 0 0 0 1.177 1.177l3.372-.675a1 1 0 0 0 .511-.273l6.07-6.07a2.91 2.91 0 0 0-.944-4.742A2.907 2.907 0 0 0 18.092 8Z"
                        clip-rule="evenodd" />
                </svg>

                <span class="font-semibold text-center text-xs sm:text-xs md:text-sm lg:text-md">Nilai Konversi
                    Satuan</span>
            </a>

            <a href="{{ route('pajak.index') }}"
                class="flex flex-col items-center justify-center p-4 bg-white rounded-lg shadow hover:bg-gray-50 dark:bg-gray-700 w-28 h-28 xs:w-28 xs:h-28 sm:w-28 sm:h-28 md:w-32 md:h-32 lg:h-36 lg:w-36 xl:h-40 xl:w-40 transition duration-300 ease-in-out transform hover:scale-105">
                <svg class="w-[48px] h-[48px] text-gray-800 dark:text-white mb-3" aria-hidden="true"
                    xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor"
                    viewBox="0 0 24 24">
                    <path fill-rule="evenodd"
                        d="M5 8a4 4 0 1 1 7.796 1.263l-2.533 2.534A4 4 0 0 1 5 8Zm4.06 5H7a4 4 0 0 0-4 4v1a2 2 0 0 0 2 2h2.172a2.999 2.999 0 0 1-.114-1.588l.674-3.372a3 3 0 0 1 .82-1.533L9.06 13Zm9.032-5a2.907 2.907 0 0 0-2.056.852L9.967 14.92a1 1 0 0 0-.273.51l-.675 3.373a1 1 0 0 0 1.177 1.177l3.372-.675a1 1 0 0 0 .511-.273l6.07-6.07a2.91 2.91 0 0 0-.944-4.742A2.907 2.907 0 0 0 18.092 8Z"
                        clip-rule="evenodd" />
                </svg>

                <span class="font-semibold text-center text-xs sm:text-xs md:text-sm lg:text-md">Pajak</span>
            </a>

            <a href="{{ route('kategori.index') }}"
                class="flex flex-col items-center justify-center p-4 bg-white rounded-lg shadow hover:bg-gray-50 dark:bg-gray-700 w-28 h-28 xs:w-28 xs:h-28 sm:w-28 sm:h-28 md:w-32 md:h-32 lg:h-36 lg:w-36 xl:h-40 xl:w-40 transition duration-300 ease-in-out transform hover:scale-105">
                <svg class="w-[48px] h-[48px] text-gray-800 dark:text-white mb-3" aria-hidden="true"
                    xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor"
                    viewBox="0 0 24 24">
                    <path fill-rule="evenodd"
                        d="M5 8a4 4 0 1 1 7.796 1.263l-2.533 2.534A4 4 0 0 1 5 8Zm4.06 5H7a4 4 0 0 0-4 4v1a2 2 0 0 0 2 2h2.172a2.999 2.999 0 0 1-.114-1.588l.674-3.372a3 3 0 0 1 .82-1.533L9.06 13Zm9.032-5a2.907 2.907 0 0 0-2.056.852L9.967 14.92a1 1 0 0 0-.273.51l-.675 3.373a1 1 0 0 0 1.177 1.177l3.372-.675a1 1 0 0 0 .511-.273l6.07-6.07a2.91 2.91 0 0 0-.944-4.742A2.907 2.907 0 0 0 18.092 8Z"
                        clip-rule="evenodd" />
                </svg>

                <span class="font-semibold text-center text-xs sm:text-xs md:text-sm lg:text-md">Data Kategori</span>
            </a>

        </div>
    </div>
</x-layout>
