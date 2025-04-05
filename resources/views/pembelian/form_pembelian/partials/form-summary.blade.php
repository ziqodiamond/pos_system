<!-- Summary Section -->
<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    <div></div> <!-- Empty space for left column -->
    <div class="space-y-3">
        <!-- Total Raw -->
        <div class="flex justify-between items-center text-sm">
            <span class="text-gray-600">Total Raw:</span>
            <span class="flex items-center justify-end h-full mr-2"
                x-text="'Rp ' + formatNumber(calculateSubtotalDisplay())"></span>
            <input type="hidden" name="subtotalBeforeDiscount" x-model="formData.subtotalSebelumDiskon">
            <input type="hidden" name="subtotalAfterDiscount" x-model="formData.subtotalSetelahDiskon">
        </div>


        <!-- Diskon -->
        <div class="flex justify-between items-center text-sm">
            <div class="flex items-center gap-2">
                <span class="text-gray-600">Diskon</span>
                <select name="diskon_type" x-model="formData.diskonType" @change="calculateTotal()"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 p-2">
                    <option value="persen">%</option>
                    <option value="nominal">Rp</option>
                </select>
            </div>
            <div class="flex items-center">
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-gray-500"
                        x-show="formData.diskonType === 'nominal' ">Rp
                    </span>
                    <template x-if="formData.diskonType === 'persen'">
                        <input type="text" name="diskon"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-40 p-2 pl-9 pr-8 text-right"
                            placeholder="0" x-model="formData.diskon" @input="calculateTotal()">
                    </template>
                    <template x-if="formData.diskonType === 'persen'">
                        <span class="absolute inset-y-0 right-3 flex items-center pointer-events-none text-gray-500">
                            %
                        </span>
                    </template>
                    <template x-if="formData.diskonType !== 'persen'">
                        <input type="text"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-40 p-2 pl-9 text-right"
                            placeholder="0" x-model.lazy="formData.diskon"
                            x-effect="$el.value = formatNumber(formData.biayaLain)"
                            @blur="$event.target.value = formatNumber(formData.diskon); calculateTotal()">
                        <input type="hidden" name="diskon" :value="toDbValue(formData.diskon)">
                    </template>
                </div>
            </div>
        </div>


        <!-- Total Diskon -->
        <div class="flex justify-between items-center text-sm"
            x-show="formData.diskonType === 'persen' && formData.diskon > 0">
            <span class="text-gray-600">Total Diskon:</span>
            <span class="flex items-center justify-end mr-2"
                x-text="'Rp ' + formatNumber(calculateTotalDiscount())"></span>
            <input type="hidden" name="total_diskon" x-model="formData.totalDiskon">
        </div>

        <!-- Total Pajak -->
        <div class="flex justify-between items-center text-sm">
            <span class="text-gray-600">Total Pajak</span>
            <span class="flex items-center justify-end mr-2" x-text="'Rp ' + formatNumber(calculateTotalTax())"></span>
            <input type="hidden" name="total_pajak" x-model="formData.totalPajak">
        </div>

        <!-- Biaya Lain -->
        <div class="flex justify-between items-center text-sm">
            <span class="text-gray-600">Biaya Lain</span>
            <div class="flex items-center">
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-gray-500">
                        Rp
                    </span>
                    <input type="text"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-40 p-2 pl-9 text-right"
                        placeholder="0" x-model.lazy="formData.biayaLain"
                        x-effect="$el.value = formatNumber(formData.biayaLain)"
                        @blur="$event.target.value = formatNumber(formData.biayaLain); calculateTotal()">

                    <input type="text" name="biaya_lain" :value="toDbValue(formData.biayaLain)">
                </div>
            </div>
        </div>


        <!-- Divider Line -->
        <div class="border-t border-gray-300 my-2"></div>

        <!-- Subtotal -->
        <div class="flex justify-between items-center text-sm font-medium mt-10" x-show="!formData.lunas" x-cloak>
            <span>Total</span>
            <span class="flex items-center justify-end mr-2"
                x-text="'Rp ' + formatNumber(calculateGrandTotal())"></span>
            <input type="hidden" name="grand_total" x-model="formData.grandTotal">
        </div>

        <!-- Total (untuk lunas) -->
        <div class="flex justify-between items-center text-sm font-medium mt-5" x-show="formData.lunas">
            <span>Total</span>
            <span class="flex items-center justify-end" x-text="'Rp ' + formatNumber(calculateGrandTotal())"></span>
            <input type="hidden" name="grand_total" x-model="formData.grandTotal">
        </div>

        <!-- Uang Muka -->
        <div class="flex justify-between items-center text-sm" x-show="!formData.lunas">
            <span class="text-gray-600">Uang Muka</span>
            <div class="flex items-center">
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-gray-500">
                        Rp
                    </span>
                    <input type="text"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-40 p-2 pl-9 text-right"
                        placeholder="0" x-model.lazy="formData.uangMuka"
                        x-effect="$el.value = formatNumber(formData.uangMuka)"
                        @blur="$event.target.value = formatNumber(formData.uangMuka); calculateTotal()">

                    <input type="text" name="uang_muka" :value="toDbValue(formData.uangMuka)">

                </div>
            </div>
        </div>

        <!-- Sisa -->
        <div class="flex justify-between items-center text-sm" x-show="!formData.lunas">
            <span class="text-gray-600">Sisa</span>
            <span class="flex items-center justify-end mr-2"
                x-text="'Rp ' + formatNumber(calculateRemaining())"></span>
            <input type="hidden" name="sisa" x-model="formData.sisa">
        </div>
    </div>
</div>
