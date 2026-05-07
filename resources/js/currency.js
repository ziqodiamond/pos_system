/**
 * Currency utility helper functions
 * Provides formatting and conversion utilities for currency values
 */

// Define Currency namespace on window
window.Currency = {
    /**
     * Format a value as currency (Rupiah)
     * @param {number} value - The value to format
     * @param {string} locale - The locale to use (default: 'id-ID')
     * @returns {string} Formatted currency string
     */
    format: function(value, locale = 'id-ID') {
        if (value === null || value === undefined) {
            return 'Rp 0';
        }
        return new Intl.NumberFormat(locale, {
            style: 'currency',
            currency: 'IDR',
            minimumFractionDigits: 0,
            maximumFractionDigits: 0,
        }).format(value);
    },

    /**
     * Convert cents to rupiah (divide by 100)
     * @param {number} cents - The value in cents
     * @returns {number} The value in rupiah
     */
    centsToRupiah: function(cents) {
        return Math.floor(cents / 100);
    },

    /**
     * Format cents as currency
     * @param {number} cents - The value in cents
     * @param {string} locale - The locale to use
     * @returns {string} Formatted currency string
     */
    formatCents: function(cents, locale = 'id-ID') {
        return this.format(this.centsToRupiah(cents), locale);
    },
};

// Global aliases for convenience
window.formatCurrency = function(value) {
    if (value === null || value === undefined) {
        return '0';
    }
    return new Intl.NumberFormat('id-ID', {
        minimumFractionDigits: 0,
        maximumFractionDigits: 0,
    }).format(Math.floor(value));
};

window.formatRupiah = function(value) {
    return window.Currency.format(value);
};

window.centsToRupiah = function(cents) {
    return window.Currency.centsToRupiah(cents);
};
