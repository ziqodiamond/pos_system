 <!-- Date -->
 <div>
     <label for="tanggal" class="block text-sm font-medium text-gray-700 mb-1">Tanggal</label>
     <input type="date" id="tanggal" name="tanggal"
         class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
         x-model="formData.tanggal">
 </div>
 <!-- Reference Number -->
 <div>
     <label for="noReferensi" class="block text-sm font-medium text-gray-700 mb-1">No.
         Referensi</label>
     <input type="text" id="noReferensi" name="no_referensi"
         class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
         x-model="formData.noReferensi" placeholder="P0000001">
 </div>
 <!-- Description -->
 <div>
     <label for="deskripsi" class="block text-sm font-medium text-gray-700 mb-1">Deskripsi</label>
     <input type="text" id="deskripsi" name="deskripsi"
         class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
         x-model="formData.deskripsi">
 </div>
