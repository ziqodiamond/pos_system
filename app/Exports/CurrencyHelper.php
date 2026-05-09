<?php

namespace App\Helpers;

/**
 * Currency Helper - Backend utility for currency operations
 * ✅ No /100 or *100 - Integer Rupiah Only
 * ✅ All values are stored and processed as whole rupiah integers
 * ✅ Display format: "3.000" (no decimals, thousand separators only)
 */
class CurrencyHelper
{
    const LOCALE = 'id-ID';
    const RUPIAH_SYMBOL = 'Rp ';

    /**
     * Format integer rupiah to locale string
     * Example: 3000 → "3.000"
     *          1500000 → "1.500.000"
     *
     * @param int|float $value Integer rupiah value
     * @return string Formatted string with thousand separators
     */
    public static function formatRupiah($value)
    {
        if (!is_numeric($value) || $value < 0) {
            return '0';
        }

        $value = (int) $value;

        return number_format($value, 0, ',', '.');
    }

    /**
     * Format with Rp symbol
     * Example: 3000 → "Rp 3.000"
     *
     * @param int|float $value Integer rupiah value
     * @return string Formatted string with symbol
     */
    public static function formatRupiahWithSymbol($value)
    {
        return self::RUPIAH_SYMBOL . self::formatRupiah($value);
    }

    /**
     * Parse rupiah string to integer
     * Example: "3.000" → 3000
     *          "Rp 1.500.000" → 1500000
     *          "1.234.567" → 1234567
     *
     * @param string $stringValue Formatted rupiah string
     * @return int Integer rupiah value
     */
    public static function parseRupiah($stringValue)
    {
        if (!is_string($stringValue)) {
            return 0;
        }

        // Remove all non-digit characters
        $cleaned = preg_replace('/\D/', '', $stringValue);

        // Parse to integer
        $value = (int) $cleaned;

        return $value >= 0 ? $value : 0;
    }

    /**
     * Validate if value is a valid currency
     * Must be non-negative integer
     *
     * @param mixed $value Value to validate
     * @return bool True if valid currency
     */
    public static function isValidCurrency($value)
    {
        return is_numeric($value) && $value >= 0 && (int) $value == $value;
    }

    /**
     * Round to nearest rupiah (integer)
     * Example: 3000.5 → 3001, 3000.4 → 3000
     *
     * @param float|int $value Value to round
     * @return int Rounded integer
     */
    public static function roundToNearestRupiah($value)
    {
        return (int) round($value);
    }

    /**
     * Floor to nearest rupiah
     *
     * @param float|int $value Value to floor
     * @return int Floored integer
     */
    public static function floorToNearestRupiah($value)
    {
        return (int) floor($value);
    }

    /**
     * Ceiling to nearest rupiah
     *
     * @param float|int $value Value to ceiling
     * @return int Ceiled integer
     */
    public static function ceilToNearestRupiah($value)
    {
        return (int) ceil($value);
    }

    /**
     * Calculate discount amount from price and discount percentage
     * Example: calculateDiscount(1000, 10) → 100
     *
     * @param int $price Base price (integer rupiah)
     * @param float $discountPercent Discount percentage (0-100)
     * @return int Discount amount (integer rupiah)
     */
    public static function calculateDiscount($price, $discountPercent)
    {
        if (!is_numeric($price) || $price < 0) {
            return 0;
        }

        if (!is_numeric($discountPercent) || $discountPercent < 0) {
            return 0;
        }

        $discountAmount = ($price * $discountPercent) / 100;

        return self::roundToNearestRupiah($discountAmount);
    }

    /**
     * Calculate tax amount from price and tax percentage
     * Example: calculateTax(1000, 12) → 120
     *
     * @param int $price Base price (integer rupiah)
     * @param float $taxPercent Tax percentage (0-100)
     * @return int Tax amount (integer rupiah)
     */
    public static function calculateTax($price, $taxPercent)
    {
        if (!is_numeric($price) || $price < 0) {
            return 0;
        }

        if (!is_numeric($taxPercent) || $taxPercent < 0) {
            return 0;
        }

        $taxAmount = ($price * $taxPercent) / 100;

        return self::roundToNearestRupiah($taxAmount);
    }

    /**
     * Calculate final price after discount
     *
     * @param int $price Base price (integer rupiah)
     * @param float $discountPercent Discount percentage (0-100)
     * @return int Price after discount (integer rupiah)
     */
    public static function calculatePriceAfterDiscount($price, $discountPercent)
    {
        if (!is_numeric($price) || $price < 0) {
            return max(0, (int) $price);
        }

        $discountAmount = self::calculateDiscount($price, $discountPercent);

        return max(0, (int) $price - $discountAmount);
    }

    /**
     * Calculate final price after discount and tax
     * Formula: (price - discount) + tax
     *
     * @param int $price Base price (integer rupiah)
     * @param float $discountPercent Discount percentage (0-100)
     * @param float $taxPercent Tax percentage (0-100)
     * @return int Final price (integer rupiah)
     */
    public static function calculateFinalPrice($price, $discountPercent, $taxPercent)
    {
        if (!is_numeric($price) || $price < 0) {
            return 0;
        }

        $afterDiscount = self::calculatePriceAfterDiscount($price, $discountPercent);
        $taxAmount = self::calculateTax($afterDiscount, $taxPercent);

        return $afterDiscount + $taxAmount;
    }

    /**
     * Calculate markup percentage
     * Markup = ((selling_price - cost_price) / cost_price) * 100
     *
     * @param int $costPrice Cost price (integer rupiah)
     * @param int $sellingPrice Selling price (integer rupiah)
     * @return float Markup percentage (2 decimals)
     */
    public static function calculateMarkup($costPrice, $sellingPrice)
    {
        if (!is_numeric($costPrice) || $costPrice <= 0) {
            return 0;
        }

        if (!is_numeric($sellingPrice) || $sellingPrice < 0) {
            return 0;
        }

        $profit = (int) $sellingPrice - (int) $costPrice;
        $markupPercent = ($profit / (int) $costPrice) * 100;

        // Round to 2 decimals
        return round($markupPercent, 2);
    }

    /**
     * Calculate margin percentage
     * Margin = ((selling_price - cost_price) / selling_price) * 100
     *
     * @param int $costPrice Cost price (integer rupiah)
     * @param int $sellingPrice Selling price (integer rupiah)
     * @return float Margin percentage (2 decimals)
     */
    public static function calculateMargin($costPrice, $sellingPrice)
    {
        if (!is_numeric($sellingPrice) || (int) $sellingPrice <= 0) {
            return 0;
        }

        if (!is_numeric($costPrice) || $costPrice < 0) {
            return 0;
        }

        $profit = (int) $sellingPrice - (int) $costPrice;
        $marginPercent = ($profit / (int) $sellingPrice) * 100;

        // Round to 2 decimals
        return round($marginPercent, 2);
    }

    /**
     * Calculate selling price from cost price and markup percentage
     * Formula: cost_price * (1 + (markup / 100))
     *
     * @param int $costPrice Cost price (integer rupiah)
     * @param float $markupPercent Markup percentage (0-100+)
     * @return int Selling price (integer rupiah)
     */
    public static function calculateSellingPriceFromMarkup($costPrice, $markupPercent)
    {
        if (!is_numeric($costPrice) || $costPrice < 0) {
            return 0;
        }

        if (!is_numeric($markupPercent) || $markupPercent < 0) {
            return (int) $costPrice;
        }

        $sellingPrice = (int) $costPrice * (1 + $markupPercent / 100);

        return self::roundToNearestRupiah($sellingPrice);
    }

    /**
     * Calculate selling price from cost price and margin percentage
     * Formula: cost_price / (1 - (margin / 100))
     *
     * @param int $costPrice Cost price (integer rupiah)
     * @param float $marginPercent Margin percentage (0-100, exclusive of 100)
     * @return int Selling price (integer rupiah)
     */
    public static function calculateSellingPriceFromMargin($costPrice, $marginPercent)
    {
        if (!is_numeric($costPrice) || $costPrice < 0) {
            return 0;
        }

        if (!is_numeric($marginPercent) || $marginPercent >= 100 || $marginPercent < 0) {
            return (int) $costPrice;
        }

        $sellingPrice = (int) $costPrice / (1 - $marginPercent / 100);

        return self::roundToNearestRupiah($sellingPrice);
    }

    /**
     * Calculate profit amount
     *
     * @param int $costPrice Cost price (integer rupiah)
     * @param int $sellingPrice Selling price (integer rupiah)
     * @return int Profit amount (integer rupiah)
     */
    public static function calculateProfit($costPrice, $sellingPrice)
    {
        if (!is_numeric($costPrice) || !is_numeric($sellingPrice)) {
            return 0;
        }

        $profit = (int) $sellingPrice - (int) $costPrice;

        return max(0, $profit);
    }

    /**
     * Check if two currency values are equal
     *
     * @param int|float $a First value
     * @param int|float $b Second value
     * @return bool True if equal
     */
    public static function isCurrencyEqual($a, $b)
    {
        $roundedA = self::roundToNearestRupiah($a);
        $roundedB = self::roundToNearestRupiah($b);

        return $roundedA === $roundedB;
    }
}
