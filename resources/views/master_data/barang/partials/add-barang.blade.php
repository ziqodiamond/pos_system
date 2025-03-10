<!-- Modal -->
<div x-show="addModal" @click.away="addModal = false"
    class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50">
    <div class="bg-white p-6 rounded-lg w-full max-w-2xl">

        <div class="flex justify-between items-center mb-4">
            <h2 class="text-lg font-semibold">Tambah Barang</h2>
            <button @click="addModal = false">
                <svg class="w-6 h-6 text-gray-800 hover:bg-gray-400 hover:rounded-full p-1 transition duration-200"
                    xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M6 18 17.94 6M18 18 6.06 6" />
                </svg>
            </button>
        </div>

        <form action="{{ route('barang.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <!-- Step 1: Nama, Kode, Kategori -->
            <div x-show="step === 1" x-cloak>
                @include('master_data.barang.partials.add-barang.step1')
            </div>

            <!-- Step 2: Harga, Diskon, Pajak, Satuan -->
            <div x-show="step === 2" x-cloak>
                @include('master_data.barang.partials.add-barang.step2')
            </div>

            <!-- Step 3: Konversi -->
            <div x-show="step === 3" x-cloak>
                @include('master_data.barang.partials.add-barang.step3')
            </div>

            <!-- Step 4: Foto -->
            <div x-show="step === 4" x-cloak>
                @include('master_data.barang.partials.add-barang.step4')
            </div>

            <!-- Navigation Buttons -->
            <div class="mt-4 flex items-center justify-between w-full relative">
                <!-- Tombol Kembali -->
                <button type="button" @click="step--" class="px-4 py-2 bg-gray-500 text-white rounded w-20"
                    :class="step === 1 ? 'invisible' : 'visible'">
                    Kembali
                </button>

                <!-- Indikator Step -->
                <div class="absolute left-1/2 transform -translate-x-1/2 flex space-x-1">
                    <template x-for="i in 4">
                        <span class="w-2 h-2 rounded-full transition-all"
                            :class="step === i ? 'bg-blue-500 scale-110' : 'bg-gray-400'"></span>
                    </template>
                </div>

                <button type="button" @click="step++" class="px-4 py-2 bg-blue-500 text-white rounded w-20"
                    :class="step === 4 ? 'invisible' : 'visible'">
                    Lanjut
                </button>

            </div>

            <!-- Simpan -->
            <button type="submit" class="mt-4 w-full px-4 py-2 bg-green-500 text-white rounded">
                Simpan
            </button>
            <button type="button" @click="addModal = false"
                class="mt-2 w-full px-4 py-2 bg-red-500 text-white rounded">
                Tutup
            </button>
        </form>

    </div>
</div>
