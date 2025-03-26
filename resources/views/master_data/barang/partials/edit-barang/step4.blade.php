<div class="flex items-center justify-center w-full">
    <label for="photo_file"
        class="relative flex flex-col items-center justify-center w-full h-64 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 dark:hover:bg-gray-800 dark:bg-gray-700 hover:bg-gray-100 dark:border-gray-600 dark:hover:border-gray-500 dark:hover:bg-gray-600">

        <input id="photo_file" name="photo_file" type="file" class="hidden" accept="image/*"
            onchange="updateFile(event)" />

        <div id="upload-area" class="flex flex-col items-center justify-center h-full">
            <svg id="upload-icon" class="w-8 h-8 mb-4 text-gray-500 dark:text-gray-400" aria-hidden="true"
                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 16">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M13 13h3a3 3 0 0 0 0-6h-.025A5.56 5.56 0 0 0 16 6.5 5.5 5.5 0 0 0 5.207 5.021C5.137 5.017 5.071 5 5 5a4 4 0 0 0 0 8h2.167M10 15V6m0 0L8 8m2-2 2 2" />
            </svg>
            <p id="upload-text" class="mb-2 text-sm text-gray-500 dark:text-gray-400"><span class="font-semibold">Foto
                    Barang</span></p>
            <p class="mb-2 text-sm text-gray-500 dark:text-gray-400"><span class="font-semibold">Click to upload</span>
                or drag and drop</p>
            <p class="text-xs text-gray-500 dark:text-gray-400">SVG, PNG, JPG or GIF (MAX. 800x400px)</p>
        </div>

        <!-- Small preview container -->
        <div id="preview-container" class="absolute inset-0 flex items-center justify-center hidden">
            <img id="image-preview" class="h-16 w-16 object-cover rounded-lg absolute top-4" />
        </div>
    </label>
</div>

<script>
    function updateFile(event) {
        const file = event.target.files[0];
        const uploadText = document.getElementById('upload-text');
        const uploadIcon = document.getElementById('upload-icon');
        const previewContainer = document.getElementById('preview-container');
        const imagePreview = document.getElementById('image-preview');

        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                imagePreview.src = e.target.result; // Set the preview image source
                uploadText.innerHTML =
                `<span class="font-semibold">File: </span>${file.name}`; // Update text to show file name
                uploadIcon.classList.add('hidden'); // Hide the upload icon
                previewContainer.classList.remove('hidden'); // Show the preview
            }
            reader.readAsDataURL(file);
        }
    }
</script>

<style>
    /* Optional: Add some styles for the upload area */
    #upload-area {
        transition: background-color 0.2s;
    }

    #upload-area:hover {
        background-color: rgba(0, 0, 0, 0.05);
        /* Light hover effect */
    }
</style>
