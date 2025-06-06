<div>
    <livewire:component.breadcrumbs />

    <div class="bg-white shadow-md rounded-lg p-4 m-2">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-semibold">Daftar Barang</h2>
            <button type="button"
                class="flex items-center justify-center px-4 py-2 text-sm font-medium text-white rounded-lg bg-primary-700 hover:bg-primary-800 focus:ring-4 focus:ring-primary-300 dark:bg-primary-600 dark:hover:bg-primary-700 focus:outline-none dark:focus:ring-primary-800">
                Export Data
            </button>
        </div>

        <div class="overflow-x-auto">

            <!-- table header here -->
            <div class="relative bg-white  dark:bg-gray-800 sm:rounded-lg">
                <div
                    class="flex flex-col items-center justify-between p-4 space-y-3 md:flex-row md:space-y-0 md:space-x-4">
                    <div class="w-full md:w-1/2">
                        <form class="flex items-center">
                            <label for="simple-search" class="sr-only">Cari</label>
                            <div class="relative w-full">
                                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                    <svg aria-hidden="true" class="w-5 h-5 text-gray-500 dark:text-gray-400"
                                        fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd"
                                            d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <input type="text" id="simple-search" wire:model="search"
                                    class="block w-full p-2 pl-10 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 
                                           focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:border-gray-600 
                                           dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                    placeholder="Cari Berdasarkan Kode dan Nama Barang">
                            </div>
                        </form>

                    </div>
                    <div
                        class="flex flex-col items-stretch justify-end flex-shrink-0 w-full space-y-2 md:w-auto md:flex-row md:space-y-0 md:items-center md:space-x-3">
                        <button type="button" @click="$dispatch('open-add-modal')"
                            class="flex items-center justify-center px-4 py-2 text-sm font-medium text-white rounded-lg bg-primary-700 hover:bg-primary-800 focus:ring-4 focus:ring-primary-300 dark:bg-primary-600 dark:hover:bg-primary-700 focus:outline-none dark:focus:ring-primary-800">
                            <svg class="h-3.5 w-3.5 mr-2" fill="currentColor" viewBox="0 0 20 20"
                                xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                <path clip-rule="evenodd" fill-rule="evenodd"
                                    d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" />
                            </svg>
                            Tambah Barang
                        </button>

                        @include('master_data.barang.partials.add-barang')

                        <div class="flex items-center w-full space-x-3 md:w-auto">

                            <button id="actionsDropdownButton" data-dropdown-toggle="actionsDropdown"
                                class="flex items-center justify-center w-full px-4 py-2 text-sm font-medium text-gray-900 bg-white border border-gray-200 rounded-lg md:w-auto focus:outline-none hover:bg-gray-100 hover:text-primary-700 focus:z-10 focus:ring-4 focus:ring-gray-200 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700"
                                type="button">
                                <svg class="-ml-1 mr-1.5 w-5 h-5" fill="currentColor" viewbox="0 0 20 20"
                                    xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                    <path clip-rule="evenodd" fill-rule="evenodd"
                                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" />
                                </svg>
                                Aksi
                            </button>
                            <div id="actionsDropdown"
                                class="z-10 hidden bg-white divide-y divide-gray-100 rounded shadow w-44 dark:bg-gray-700 dark:divide-gray-600">
                                <ul class="py-1 text-sm text-gray-700 dark:text-gray-200"
                                    aria-labelledby="actionsDropdownButton">
                                    <li>
                                        <a href="#"
                                            class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Edit
                                        </a>
                                    </li>
                                </ul>
                                <div class="py-1">
                                    <a href="#"
                                        class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 dark:text-gray-200 dark:hover:text-white">Hapus</a>
                                </div>
                            </div>
                            <button id="filterDropdownButton" data-dropdown-toggle="filterDropdown"
                                class="flex items-center justify-center w-full px-4 py-2 text-sm font-medium text-gray-900 bg-white border border-gray-200 rounded-lg md:w-auto focus:outline-none hover:bg-gray-100 hover:text-primary-700 focus:z-10 focus:ring-4 focus:ring-gray-200 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700"
                                type="button">
                                <svg xmlns="http://www.w3.org/2000/svg" aria-hidden="true"
                                    class="w-4 h-4 mr-2 text-gray-400" viewbox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd"
                                        d="M3 3a1 1 0 011-1h12a1 1 0 011 1v3a1 1 0 01-.293.707L12 11.414V15a1 1 0 01-.293.707l-2 2A1 1 0 018 17v-5.586L3.293 6.707A1 1 0 013 6V3z"
                                        clip-rule="evenodd" />
                                </svg>
                                Filter
                                <svg class="-mr-1 ml-1.5 w-5 h-5" fill="currentColor" viewbox="0 0 20 20"
                                    xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                    <path clip-rule="evenodd" fill-rule="evenodd"
                                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a11 0 010-1.414z" />
                                </svg>
                            </button>
                            <!-- Dropdown menu -->
                            <div id="filterDropdown"
                                class="z-10 hidden w-48 p-3 bg-white rounded-lg shadow dark:bg-gray-700">
                                <h6 class="mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                    Urutkan Berdasarkan
                                </h6>
                                <ul class="space-y-2 text-sm mb-2" aria-labelledby="dropdownDefault">
                                    <li class="flex items-center">
                                        <input id="terbaru" type="checkbox" value="terbaru"
                                            class="w-4 h-4 bg-gray-100 border-gray-300 rounded text-primary-600 focus:ring-primary-500 dark:focus:ring-primary-600 dark:ring-offset-gray-700 focus:ring-2 dark:bg-gray-600 dark:border-gray-500" />
                                        <label for="terbaru"
                                            class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-100">
                                            Terbaru
                                        </label>
                                    </li>
                                    <li class="flex items-center">
                                        <input id="terlama" type="checkbox" value="terlama"
                                            class="w-4 h-4 bg-gray-100 border-gray-300 rounded text-primary-600 focus:ring-primary-500 dark:focus:ring-primary-600 dark:ring-offset-gray-700 focus:ring-2 dark:bg-gray-600 dark:border-gray-500" />
                                        <label for="terlama"
                                            class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-100">
                                            Terlama
                                        </label>
                                    </li>
                                </ul>
                                <h6 class="mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                    Status
                                </h6>
                                <ul class="space-y-2 text-sm mb-2" aria-labelledby="dropdownDefault">
                                    <li class="flex items-center">
                                        <input id="aktif" type="checkbox" value="aktif"
                                            class="w-4 h-4 bg-gray-100 border-gray-300 rounded text-primary-600 focus:ring-primary-500 dark:focus:ring-primary-600 dark:ring-offset-gray-700 focus:ring-2 dark:bg-gray-600 dark:border-gray-500" />
                                        <label for="aktif"
                                            class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-100">
                                            Aktif
                                        </label>
                                    </li>
                                    <li class="flex items-center">
                                        <input id="nonaktif" type="checkbox" value="nonaktif"
                                            class="w-4 h-4 bg-gray-100 border-gray-300 rounded text-primary-600 focus:ring-primary-500 dark:focus:ring-primary-600 dark:ring-offset-gray-700 focus:ring-2 dark:bg-gray-600 dark:border-gray-500" />
                                        <label for="nonaktif"
                                            class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-100">
                                            Nonaktif
                                        </label>
                                    </li>
                                    <li class="flex items-center">
                                        <input id="diskon" type="checkbox" value="diskon"
                                            class="w-4 h-4 bg-gray-100 border-gray-300 rounded text-primary-600 focus:ring-primary-500 dark:focus:ring-primary-600 dark:ring-offset-gray-700 focus:ring-2 dark:bg-gray-600 dark:border-gray-500" />
                                        <label for="diskon"
                                            class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-100">
                                            Diskon
                                        </label>
                                    </li>
                                </ul>
                                <h6 class="mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                    Pajak
                                </h6>
                                <ul class="space-y-2 text-sm mb-2" aria-labelledby="dropdownDefault">
                                    @foreach ($pajaks as $pajak)
                                        <li class="flex items-center">
                                            <input id="pajak-{{ $pajak->id }}" type="checkbox"
                                                value="{{ $pajak->id }}"
                                                class="w-4 h-4 bg-gray-100 border-gray-300 rounded text-primary-600 focus:ring-primary-500 dark:focus:ring-primary-600 dark:ring-offset-gray-700 focus:ring-2 dark:bg-gray-600 dark:border-gray-500" />
                                            <label for="pajak-{{ $pajak->id }}"
                                                class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-100">
                                                {{ $pajak->nama }}
                                            </label>
                                        </li>
                                    @endforeach
                                </ul>
                                <h6 class="mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                    Kategori
                                </h6>
                                <ul class="space-y-2 text-sm mb-2" aria-labelledby="dropdownDefault">
                                    @foreach ($kategoris as $kategori)
                                        <li class="flex items-center">
                                            <input id="kategori-{{ $kategori->id }}" type="checkbox"
                                                value="{{ $kategori->id }}"
                                                class="w-4 h-4 bg-gray-100 border-gray-300 rounded text-primary-600 focus:ring-primary-500 dark:focus:ring-primary-600 dark:ring-offset-gray-700 focus:ring-2 dark:bg-gray-600 dark:border-gray-500" />
                                            <label for="kategori-{{ $kategori->id }}"
                                                class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-100">
                                                {{ $kategori->nama }}
                                            </label>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

            </div>


            {{-- table --}}
            <div class="overflow-x-auto shadow-md sm:rounded-lg mt-2">
                <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400 shadow:md">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-100 dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                            <th scope="col" class="p-4">
                                <div class="flex items-center">
                                    <input id="checkbox-all-search" type="checkbox"
                                        class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded-sm focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 dark:focus:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                    <label for="checkbox-all-search" class="sr-only">checkbox</label>
                                </div>
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Kode
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Nama Barang
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Kategori
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Harga Pokok
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Harga Jual
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Diskon
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Pajak
                            </th>

                            <th scope="col" class="px-6 py-3">
                                Stok
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Action
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($barang as $item)
                            <tr
                                class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 border-gray-200 hover:bg-gray-100 dark:hover:bg-gray-600">
                                <td class="w-4 p-4">
                                    <div class="flex items-center">
                                        <input id="checkbox-table-search-3" type="checkbox"
                                            class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded-sm focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 dark:focus:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                        <label for="checkbox-table-search-3" class="sr-only">checkbox</label>
                                    </div>
                                </td>
                                <th scope="row"
                                    class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                    {{ $item->kode }}
                                </th>
                                <td class="px-6 py-4">
                                    {{ $item->nama }}
                                </td>
                                <td class="px-6 py-4">
                                    {{ $item->kategori->nama }}
                                </td>
                                <td class="px-6 py-4">
                                    {{ $item->harga_pokok }}

                                </td>
                                <td class="px-6 py-4">
                                    {{ $item->harga_jual }}
                                </td>
                                <td class="px-6 py-4">
                                    {{ $item->diskon }}
                                </td>
                                <td class="px-6 py-4">
                                    {{ $item->pajak }}
                                </td>
                                <td class="px-6 py-4">
                                    {{ $item->stok }}
                                </td>
                                <td class="flex items-center px-6 py-4">
                                    <a href="#"
                                        class="font-medium text-blue-600 dark:text-blue-500 hover:underline">Edit</a>
                                    <a href="#"
                                        class="font-medium text-red-600 dark:text-red-500 hover:underline ms-3">Remove</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                                    Barang Kosong
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                {{ $barang->links() }}

            </div>

        </div>


    </div>
    <script>
        function toggleDropdown(event, id) {
            event.stopPropagation();
            document.querySelectorAll('[id^="dropdown-"]').forEach(el => {
                if (el.id !== id) el.classList.add('hidden');
            });
            document.getElementById(id).classList.toggle('hidden');
        }

        document.addEventListener('click', function() {
            document.querySelectorAll('[id^="dropdown-"]').forEach(el => el.classList.add('hidden'));
        });
    </script>

</div>
