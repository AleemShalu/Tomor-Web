<?php

/**
 * get vat SA standard rate.
 */

use App\Models\TaxCode;

if ( ! function_exists( 'getVatCode' ) ) {
    function getVatCode( $code = NULL )
    {
        if ( is_null( $code ) ) {
            $taxCode = TaxCode::where( 'code', 'S' )->first(); // standard KSA VAT code
        } else {
            $taxCode = TaxCode::where( 'code', $code )->first();
        }

        return $taxCode;
    }
}

/**
 * get vat SA standard rate.
 */
if ( ! function_exists( 'getVatRate' ) ) {
    function getVatRate( $code = NULL )
    {
        if ( is_null( $code ) ) {

            $taxCodeRate = (float) TaxCode::where( 'code', 'VAT' )->first()->tax_rate ?? 15; // standard KSA VAT rate
        } else {
            $taxCodeRate = (float) TaxCode::where( 'code', $code )->first()->tax_rate ?? 0;
        }

        return $taxCodeRate;
    }
}

/**
 * calculate vat amount  from vat included amount.
 */
if ( ! function_exists( 'calculateAmountExcludingVat' ) ) {
    function calculateAmountExcludingVat( $baseAmount, $taxCode = NULL )
    {
        $taxCodeRate = getVatRate( $taxCode );

        return (float) $baseAmount / ( 1 + ( $taxCodeRate / 100 ) );
    }
}

/**
 * calculate vat amount  from vat included amount.
 */
if ( ! function_exists( 'calculateVatFromVatIncludedAmount' ) ) {
    function calculateVatFromVatIncludedAmount( $baseAmount, $taxCode = NULL )
    {
        $taxCodeRate = getVatRate( $taxCode );

        return round( $baseAmount * ( $taxCodeRate / ( $taxCodeRate + 100 ) ), 2 );
    }
}

/**
 * calculate vat amount.
 */
if ( ! function_exists( 'calculateVat' ) ) {
    function calculateVat( $baseAmount, $taxCode = NULL )
    {
        $taxCodeRate = getVatRate( $taxCode );

        return round( ( $baseAmount * $taxCodeRate ) / 100, 2 );
    }
}

/**
 * get amount including vat.
 */
if ( ! function_exists( 'getAmountIncludingVate' ) ) {
    function getAmountIncludingVate( $baseAmount, $taxCode = NULL )
    {
        $taxCodeRate = getVatRate( $taxCode );

        return (float) $baseAmount * ( 1 + ( $taxCodeRate / 100 ) );
    }
}

if (!function_exists('convertToTwoDecimalPlaces')) {
    /**
     * Convert a number to two decimal places and ensure it is returned as a float.
     *
     * @param float $value
     * @return float
     */
    function convertToTwoDecimalPlaces($value)
    {
        return floatval(number_format((float)$value, 2, '.', ''));
    }
}


/**
 * Returns tafqeet.
 */
// if ( ! function_exists( 'tafqeet' ) ) {
//     function tafqeet($baseAmount, $currencyCode, $locale = '' )
//     {
//         if (is_null($baseAmount) )
//             $baseAmount = 0;

//         try {
//             if ($locale == 'ar' ) {
//                 return Tafqeet::inArabic($baseAmount, strtolower($currencyCode) );
//                 // return Numbers::TafqeetMoney($price, $currencyCode);
//             }
//             return NumberToWords::transformCurrency(($locale ?? app()->getLocale() ), $baseAmount, $currencyCode);
//         }
//         catch (NumberToWordsException $ex) {
//             return false;
//         }
//     }
// }

/**
 * Returns NumberToWords.
 */

// if ( ! function_exists( 'nuumberToWords' ) ) {
//     function nuumberToWords($baseAmount, $currencyCode, $locale = '' )
//     {
//         if (is_null($baseAmount) )
//             $baseAmount = 0;

//         try {
//             return NumberToWords::transformCurrency(($locale ?? app()->getLocale() ), $baseAmount, $currencyCode);
//         }
//         catch (NumberToWordsException $ex) {
//             return false;
//         }
//     }
// }
