<x-layout>


    @include('components.breadcrumbs')
    <div class="bg-white shadow-md rounded-lg p-4 m-2">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-semibold">Daftar Konversi</h2>
            <button type="button"
                class="flex items-center justify-center px-4 py-2 text-sm font-medium text-white rounded-lg bg-primary-700 hover:bg-primary-800 focus:ring-4 focus:ring-primary-300 dark:bg-primary-600 dark:hover:bg-primary-700 focus:outline-none dark:focus:ring-primary-800">
                Export Data
            </button>
        </div>

        <div class="overflow-x-auto">

            <!-- table header here -->
            <div class="relative bg-white  dark:bg-gray-800 sm:rounded-lg">
                @include('master_data.konversi.partials.table-header')
            </div>


            {{-- table --}}
            <div id="table-container" class="overflow-x-auto shadow-md sm:rounded-lg mt-2">
                @include('master_data.konversi.partials.table')
            </div>




        </div>


    </div>

    <script>
        const reportSearchRoute = @json(route('konversi.index'));

        document.addEventListener('DOMContentLoaded', () => {
            // Setup event listeners
            document.getElementById('search').addEventListener('input', handleSearchAndFilter);
            document.querySelectorAll('.filter-input').forEach(input => {
                input.addEventListener('change', handleSearchAndFilter);
            });

            function handleSearchAndFilter() {
                const selectedFilters = {};

                // Ambil nilai search
                const query = encodeURIComponent(document.getElementById('search').value);

                // Ambil semua filter yang dicek
                document.querySelectorAll('.filter-input:checked').forEach(input => {
                    selectedFilters[input.name] = input.value;
                });

                const queryString = new URLSearchParams({
                    ...selectedFilters,
                    search: query
                }).toString();

                fetch(`${reportSearchRoute}?${queryString}`, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(response => {
                        if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);
                        return response.json();
                    })
                    .then(data => {
                        document.getElementById('table-container').innerHTML = data.html;
                    })
                    .catch(error => console.error('Error fetching filter results:', error));
            }
        });
    </script>

</x-layout>
