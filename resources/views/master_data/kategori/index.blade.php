<x-layout>


    <div class="bg-white shadow-md rounded-lg p-4 m-2">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-semibold">Daftar Kategori</h2>
            <button type="button"
                class="flex items-center justify-center px-4 py-2 text-sm font-medium text-white rounded-lg bg-primary-700 hover:bg-primary-800 focus:ring-4 focus:ring-primary-300 dark:bg-primary-600 dark:hover:bg-primary-700 focus:outline-none dark:focus:ring-primary-800">
                Export Data
            </button>
        </div>

        <div class="overflow-x-auto">

            <!-- table header here -->
            <div class="relative bg-white  dark:bg-gray-800 sm:rounded-lg">
                @include('master_data.kategori.partials.table-header')
            </div>


            {{-- table --}}
            <div class="overflow-x-auto shadow-md sm:rounded-lg mt-2">
                @include('master_data.kategori.partials.table')
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

</x-layout>
