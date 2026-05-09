/**
 * Currency utility helper functions
 * Provides formatting and conversion utilities for currency values
 */

window.Currency = {
    /**
     * Format integer rupiah ke string tampilan (tanpa simbol Rp)
     * Contoh: 1500000 → "1.500.000"
     */
    formatRupiah: function (value) {
        if (value === null || value === undefined || isNaN(value)) return "0";
        return new Intl.NumberFormat("id-ID", {
            minimumFractionDigits: 0,
            maximumFractionDigits: 0,
        }).format(Math.round(value));
    },

    /**
     * Parse string tampilan ke integer rupiah
     * Contoh: "1.500.000" → 1500000
     * Contoh: "1,500,000" → 1500000
     */
    parseRupiah: function (value) {
        if (value === null || value === undefined || value === "") return 0;
        if (typeof value === "number") return Math.round(value);

        // Hapus semua karakter selain digit
        const cleaned = String(value).replace(/[^\d]/g, "");
        return parseInt(cleaned, 10) || 0;
    },

    /**
     * Format dengan simbol mata uang lengkap
     * Contoh: 1500000 → "Rp 1.500.000"
     */
    format: function (value, locale = "id-ID") {
        if (value === null || value === undefined) return "Rp 0";
        return new Intl.NumberFormat(locale, {
            style: "currency",
            currency: "IDR",
            minimumFractionDigits: 0,
            maximumFractionDigits: 0,
        }).format(value);
    },

    /**
     * Konversi cents ke rupiah
     */
    centsToRupiah: function (cents) {
        return Math.floor(cents / 100);
    },

    formatCents: function (cents, locale = "id-ID") {
        return this.format(this.centsToRupiah(cents), locale);
    },
};

// Global aliases
window.formatCurrency = function (value) {
    return window.Currency.formatRupiah(value);
};

window.formatRupiah = function (value) {
    return window.Currency.format(value);
};

window.parseRupiah = function (value) {
    return window.Currency.parseRupiah(value);
};

window.centsToRupiah = function (cents) {
    return window.Currency.centsToRupiah(cents);
};
