<?php


use GeniusTS\HijriDate\Hijri;


if (!function_exists('convertHijriToGregorian')) {
    function convertHijriToGregorian($date, $separator)
    {
        // Convert the Hijri date string to an array of year, month, and day
        $dateParts = explode($separator, $date);
        $day = (int)$dateParts[0];
        $month = (int)$dateParts[1];
        $year = (int)$dateParts[2];

        // Convert the Hijri date to the Gregorian date
        $gregorianDate = Hijri::convertToGregorian($year, $month, $day);

        // Format the Gregorian date as needed (e.g., "Y-m-d")
        $formattedGregorianDate = $gregorianDate->format('Y-m-d');

        return $formattedGregorianDate;
    }
}

/**
 * Returns all currencies.
 */
if (!function_exists('convertGregorianToHijri')) {
    function convertGregorianToHijri($date)
    {
        return Hijri::convertToHijri($date)->format('Y-m-d');
    }
}

if (!function_exists('convertGregorianToHijriUsingSlash')) {
    function convertGregorianToHijriUsingSlash($date)
    {
        return Hijri::convertToHijri($date)->format('Y/m/d');
    }
}

