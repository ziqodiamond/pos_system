<x-layout>
    <div>
        @include('components.breadcrumbs')
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
                <svg class="w-[48px] h-[48px] text-gray-800 dark:text-white" aria-hidden="true"
                    xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor"
                    viewBox="0 0 24 24">
                    <path
                        d="M10.83 5a3.001 3.001 0 0 0-5.66 0H4a1 1 0 1 0 0 2h1.17a3.001 3.001 0 0 0 5.66 0H20a1 1 0 1 0 0-2h-9.17ZM4 11h9.17a3.001 3.001 0 0 1 5.66 0H20a1 1 0 1 1 0 2h-1.17a3.001 3.001 0 0 1-5.66 0H4a1 1 0 1 1 0-2Zm1.17 6H4a1 1 0 1 0 0 2h1.17a3.001 3.001 0 0 0 5.66 0H20a1 1 0 1 0 0-2h-9.17a3.001 3.001 0 0 0-5.66 0Z" />
                </svg>


                <span class="font-semibold text-center text-xs sm:text-xs md:text-sm lg:text-md">Data Satuan</span>
            </a>

            <a href="{{ route('konversi.index') }}"
                class="flex flex-col items-center justify-center p-4 bg-white rounded-lg shadow hover:bg-gray-50 dark:bg-gray-700 w-28 h-28 xs:w-28 xs:h-28 sm:w-28 sm:h-28 md:w-32 md:h-32 lg:h-36 lg:w-36 xl:h-40 xl:w-40 transition duration-300 ease-in-out transform hover:scale-105">
                <svg class="w-[48px] h-[48px] text-gray-800 dark:text-white" aria-hidden="true"
                    xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor"
                    viewBox="0 0 24 24">
                    <path fill-rule="evenodd"
                        d="M12 4a1 1 0 1 0 0 2 1 1 0 0 0 0-2Zm-2.952.462c-.483.19-.868.432-1.19.71-.363.315-.638.677-.831.93l-.106.14c-.21.268-.36.418-.574.527C6.125 6.883 5.74 7 5 7a1 1 0 0 0 0 2c.364 0 .696-.022 1-.067v.41l-1.864 4.2a1.774 1.774 0 0 0 .821 2.255c.255.133.538.202.825.202h2.436a1.786 1.786 0 0 0 1.768-1.558 1.774 1.774 0 0 0-.122-.899L8 9.343V8.028c.2-.188.36-.38.495-.553.062-.079.118-.15.168-.217.185-.24.311-.406.503-.571a1.89 1.89 0 0 1 .24-.177A3.01 3.01 0 0 0 11 7.829V20H5.5a1 1 0 1 0 0 2h13a1 1 0 1 0 0-2H13V7.83a3.01 3.01 0 0 0 1.63-1.387c.206.091.373.19.514.29.31.219.532.465.811.78l.025.027.02.023v1.78l-1.864 4.2a1.774 1.774 0 0 0 .821 2.255c.255.133.538.202.825.202h2.436a1.785 1.785 0 0 0 1.768-1.558 1.773 1.773 0 0 0-.122-.899L18 9.343v-.452c.302.072.633.109 1 .109a1 1 0 1 0 0-2c-.48 0-.731-.098-.899-.2-.2-.12-.363-.293-.651-.617l-.024-.026c-.267-.3-.622-.7-1.127-1.057a5.152 5.152 0 0 0-1.355-.678 3.001 3.001 0 0 0-5.896.04Z"
                        clip-rule="evenodd" />
                </svg>


                <span class="font-semibold text-center text-xs sm:text-xs md:text-sm lg:text-md">Nilai Konversi
                    Satuan</span>
            </a>

            <a href="{{ route('pajak.index') }}"
                class="flex flex-col items-center justify-center p-4 bg-white rounded-lg shadow hover:bg-gray-50 dark:bg-gray-700 w-28 h-28 xs:w-28 xs:h-28 sm:w-28 sm:h-28 md:w-32 md:h-32 lg:h-36 lg:w-36 xl:h-40 xl:w-40 transition duration-300 ease-in-out transform hover:scale-105">
                <svg class="w-[48px] h-[48px] text-gray-800 dark:text-white mb-3" xmlns="http://www.w3.org/2000/svg"
                    width="24" height="24" fill="currentColor" class="bi bi-cash-coin" viewBox="0 0 16 16">
                    <path fill-rule="evenodd"
                        d="M11 15a4 4 0 1 0 0-8 4 4 0 0 0 0 8m5-4a5 5 0 1 1-10 0 5 5 0 0 1 10 0" />
                    <path
                        d="M9.438 11.944c.047.596.518 1.06 1.363 1.116v.44h.375v-.443c.875-.061 1.386-.529 1.386-1.207 0-.618-.39-.936-1.09-1.1l-.296-.07v-1.2c.376.043.614.248.671.532h.658c-.047-.575-.54-1.024-1.329-1.073V8.5h-.375v.45c-.747.073-1.255.522-1.255 1.158 0 .562.378.92 1.007 1.066l.248.061v1.272c-.384-.058-.639-.27-.696-.563h-.668zm1.36-1.354c-.369-.085-.569-.26-.569-.522 0-.294.216-.514.572-.578v1.1zm.432.746c.449.104.655.272.655.569 0 .339-.257.571-.709.614v-1.195z" />
                    <path
                        d="M1 0a1 1 0 0 0-1 1v8a1 1 0 0 0 1 1h4.083q.088-.517.258-1H3a2 2 0 0 0-2-2V3a2 2 0 0 0 2-2h10a2 2 0 0 0 2 2v3.528c.38.34.717.728 1 1.154V1a1 1 0 0 0-1-1z" />
                    <path d="M9.998 5.083 10 5a2 2 0 1 0-3.132 1.65 6 6 0 0 1 3.13-1.567" />
                </svg>

                <span class="font-semibold text-center text-xs sm:text-xs md:text-sm lg:text-md">Pajak</span>
            </a>

            <a href="{{ route('kategori.index') }}"
                class="flex flex-col items-center justify-center p-4 bg-white rounded-lg shadow hover:bg-gray-50 dark:bg-gray-700 w-28 h-28 xs:w-28 xs:h-28 sm:w-28 sm:h-28 md:w-32 md:h-32 lg:h-36 lg:w-36 xl:h-40 xl:w-40 transition duration-300 ease-in-out transform hover:scale-105">
                <svg class="w-[48px] h-[48px] text-gray-800 dark:text-white mb-3" aria-hidden="true"
                    xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor"
                    viewBox="0 0 24 24">
                    <path fill-rule="evenodd"
                        d="M2 6a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V6Zm4.996 2a1 1 0 0 0 0 2h.01a1 1 0 1 0 0-2h-.01ZM11 8a1 1 0 1 0 0 2h6a1 1 0 1 0 0-2h-6Zm-4.004 3a1 1 0 1 0 0 2h.01a1 1 0 1 0 0-2h-.01ZM11 11a1 1 0 1 0 0 2h6a1 1 0 1 0 0-2h-6Zm-4.004 3a1 1 0 1 0 0 2h.01a1 1 0 1 0 0-2h-.01ZM11 14a1 1 0 1 0 0 2h6a1 1 0 1 0 0-2h-6Z"
                        clip-rule="evenodd" />
                </svg>

                <span class="font-semibold text-center text-xs sm:text-xs md:text-sm lg:text-md">Data Kategori</span>
            </a>

        </div>
    </div>
</x-layout>
