@props([
    'id',
    'title' => 'Modal Title',
    'size' => 'max-w-2xl',
    'triggerText' => 'Open Modal',
    'triggerClass' => 'text-blue-600 hover:underline',
])

<div x-data="{ openModal: null }">
    <!-- Tombol Trigger Modal dengan teks dan style yang bisa diubah -->
    <button type="button" x-on:click="openModal = '{{ $id }}'" class="{{ $triggerClass }}">
        {{ $triggerText }}
    </button>

    <!-- Background Overlay -->
    <div x-show="openModal === '{{ $id }}'" class="fixed inset-0 bg-black bg-opacity-50 z-40" aria-hidden="true">
    </div>

    <!-- Modal -->
    <div x-show="openModal === '{{ $id }}'" x-transition.opacity.duration.300ms
        class="fixed top-0 left-0 right-0 z-50 w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-full flex items-center justify-center">
        <div class="relative w-full {{ $size }} max-h-full z-50" x-on:click.outside="openModal = null">
            <!-- Modal content -->
            <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                <!-- Header -->
                <div class="flex items-start justify-between p-4 border-b rounded-t dark:border-gray-600">
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                        {{ $title }}
                    </h3>
                    <button type="button" x-on:click="openModal = null"
                        class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-gray-600 dark:hover:text-white">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                clip-rule="evenodd"></path>
                        </svg>
                    </button>
                </div>

                <!-- Isi Modal -->
                <div class="p-6 space-y-6">
                    {{ $slot }}
                </div>
            </div>
        </div>
    </div>
</div>
