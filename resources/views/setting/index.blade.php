<x-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <!-- Header -->
                    <div class="mb-6">
                        <h2 class="text-2xl font-bold text-gray-800">Pengaturan Toko</h2>
                        <p class="text-gray-600">Kelola informasi dasar toko Anda.</p>
                    </div>

                    <!-- Pesan Sukses -->
                    @if (session('success'))
                        <div class="mb-4 px-4 py-3 leading-normal text-green-700 bg-green-100 rounded-lg" role="alert">
                            {{ session('success') }}
                        </div>
                    @endif

                    <!-- Form -->
                    <form action="{{ route('settings.update') }}" method="POST" class="space-y-6">
                        @csrf

                        <!-- Nama Toko -->
                        <div>
                            <label for="toko_nama" class="block text-sm font-medium text-gray-700">Nama Toko</label>
                            <input type="text" name="toko_nama" id="toko_nama" value="{{ $tokoData['toko_nama'] }}"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            @error('toko_nama')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Alamat Toko -->
                        <div>
                            <label for="toko_alamat" class="block text-sm font-medium text-gray-700">Alamat Toko</label>
                            <textarea name="toko_alamat" id="toko_alamat" rows="3"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">{{ $tokoData['toko_alamat'] }}</textarea>
                            @error('toko_alamat')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Kota dan Provinsi -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="toko_kota" class="block text-sm font-medium text-gray-700">Kota</label>
                                <input type="text" name="toko_kota" id="toko_kota"
                                    value="{{ $tokoData['toko_kota'] }}"
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                @error('toko_kota')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="toko_provinsi"
                                    class="block text-sm font-medium text-gray-700">Provinsi</label>
                                <input type="text" name="toko_provinsi" id="toko_provinsi"
                                    value="{{ $tokoData['toko_provinsi'] }}"
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                @error('toko_provinsi')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Telepon dan Email -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="toko_telepon" class="block text-sm font-medium text-gray-700">Nomor
                                    Telepon</label>
                                <input type="text" name="toko_telepon" id="toko_telepon"
                                    value="{{ $tokoData['toko_telepon'] }}"
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                @error('toko_telepon')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="toko_email" class="block text-sm font-medium text-gray-700">Email</label>
                                <input type="email" name="toko_email" id="toko_email"
                                    value="{{ $tokoData['toko_email'] }}"
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                @error('toko_email')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- NPWP -->
                        <div>
                            <label for="toko_npwp" class="block text-sm font-medium text-gray-700">NPWP</label>
                            <input type="text" name="toko_npwp" id="toko_npwp" value="{{ $tokoData['toko_npwp'] }}"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            @error('toko_npwp')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Tombol Simpan -->
                        <div class="flex justify-end">
                            <button type="submit"
                                class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Simpan Pengaturan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-layout>
