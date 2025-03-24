@props(['filterGroups' => []])

<div>
    <!-- Dropdown trigger -->
    <button id="filterDropdownButton" data-dropdown-toggle="filterDropdown"
        class="flex items-center justify-center w-full px-4 py-2 text-sm font-medium text-gray-900 bg-white border border-gray-200 rounded-lg md:w-auto focus:outline-none hover:bg-gray-100 hover:text-primary-700 focus:z-10 focus:ring-4 focus:ring-gray-200 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700"
        type="button">
        <svg xmlns="http://www.w3.org/2000/svg" aria-hidden="true" class="w-4 h-4 mr-2 text-gray-400" viewbox="0 0 20 20"
            fill="currentColor">
            <path fill-rule="evenodd"
                d="M3 3a1 1 0 011-1h12a1 1 0 011 1v3a1 1 0 01-.293.707L12 11.414V15a1 1 0 01-.293.707l-2 2A1 1 0 018 17v-5.586L3.293 6.707A1 1 0 013 6V3z"
                clip-rule="evenodd" />
        </svg>
        Filter
        <svg class="-mr-1 ml-1.5 w-5 h-5" fill="currentColor" viewbox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"
            aria-hidden="true">
            <path clip-rule="evenodd" fill-rule="evenodd"
                d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a11 0 010-1.414z" />
        </svg>
    </button>


    <!-- Dropdown menu -->
    <div id="filterDropdown" class="z-10 hidden w-48 p-3 bg-white rounded-lg shadow dark:bg-gray-700">
        @foreach ($filterGroups as $groupName => $filters)
            <h6 class="mb-2 text-sm font-medium text-gray-900 dark:text-white">
                {{ ucfirst($groupName) }}
            </h6>
            <ul class="space-y-2 text-sm mb-2" aria-labelledby="dropdownDefault">
                @foreach ($filters as $filter)
                    <li class="flex items-center">
                        <input type="radio" id="{{ $filter['kode'] }}" name="{{ $groupName }}"
                            value="{{ $filter['kode'] }}"
                            class="filter-input w-4 h-4 bg-gray-100 border-gray-300 rounded text-primary-600 focus:ring-primary-500 dark:focus:ring-primary-600 dark:ring-offset-gray-700 focus:ring-2 dark:bg-gray-600 dark:border-gray-500" />
                        <label for="{{ $filter['kode'] }}"
                            class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-100">
                            {{ ucfirst($filter['nama']) }}
                        </label>
                    </li>
                @endforeach
            </ul>
        @endforeach
    </div>
</div>
