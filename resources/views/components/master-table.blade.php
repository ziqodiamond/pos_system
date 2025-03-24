@props(['route', 'items', 'columns'])

<form action="{{ route($route . '.bulkAction') }}" method="POST" id="bulkForm">
    @csrf
    <input type="hidden" name="action" id="bulkActionType" value="">

    <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400 shadow:md">
        <thead class="text-xs text-gray-700 uppercase bg-gray-100 dark:bg-gray-700 dark:text-gray-400">
            <tr>
                <th scope="col" class="p-4">
                    <div class="flex items-center">
                        <input id="checkbox-all" type="checkbox" onclick="toggleCheckboxes(this)"
                            class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded-sm focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 dark:focus:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                        <label for="checkbox-all" class="sr-only">checkbox</label>
                    </div>
                </th>
                @foreach ($columns as $col)
                    <th scope="col" class="px-6 py-3">{{ $col }}</th>
                @endforeach
                <th scope="col" class="px-6 py-3">Action</th>
            </tr>
        </thead>
        <tbody>
            {{ $slot }}
        </tbody>
    </table>
</form>
