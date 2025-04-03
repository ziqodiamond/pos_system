  <!-- Supplier Dropdown -->
  <div x-data="dropdown('supplier')">
      <label for="supplier" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Supplier</label>
      <div class="relative">
          <input type="text" x-model="search" @input="watchSearch()" @click="openDropdown()" autocomplete="off"
              placeholder="Cari supplier..."
              class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" />
          <ul x-show="open" @click.outside="close()" x-cloak
              class="absolute z-10 bg-white border border-gray-300 rounded-lg shadow-lg mt-1 w-full max-h-40 overflow-y-auto">
              <template x-for="supplier in filteredSuppliers" :key="supplier.id">
                  <li @click="select(supplier)" class="px-4 py-2 cursor-pointer hover:bg-gray-100">
                      <span x-text="supplier.nama"></span>
                  </li>
              </template>
          </ul>
          <input type="hidden" name="supplier_id" x-model="selected.id">
      </div>
  </div>
  <!-- Nomor Faktur -->
  <div>
      <label for="noFaktur" class="block text-sm font-medium text-gray-700 mb-1">No. Faktur</label>
      <input type="text" id="noFaktur" name="no_faktur" x-model="formData.noFaktur"
          class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
          placeholder="Isi nomor faktur">
  </div>
  <!-- Checkbox for Lunas -->
  <div class="flex items-center mt-6">
      <input id="lunas" type="checkbox" name="lunas"
          class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500"
          x-model="formData.lunas">
      <label for="lunas" class="ml-2 mr-4 text-sm font-medium text-gray-900">Lunas</label>
      <input id="lunas" type="checkbox" name="include_pajak"
          class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500"
          x-model="formData.include_pajak">
      <label for="include_pajak" class="ml-2 text-sm font-medium text-gray-900">Harga Termasuk Pajak</label>
  </div>
