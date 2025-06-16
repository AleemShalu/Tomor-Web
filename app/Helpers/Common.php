<?php

use App\Enums\TimezoneEnum;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

// use AliAbdalla\Tafqeet\Core\Tafqeet;


if (!function_exists('log_alert')) {
    function log_alert($data)
    {
        return Log::alert($data);
    }
}

if (!function_exists('getSecondaryStorageDisk')) {
    function getSecondaryStorageDisk()
    {
        return env('SECONDARY_FILE_DISK', 'local');
    }
}

if (!function_exists('getMicroTime')) {
    function getMicroTime()
    {
        list($usec, $sec) = explode(" ", microtime());
        $rr = explode('.', $usec);
        return $sec . substr($rr[1], 0, 3);
    }
}

if (!function_exists('getConstantsValue')) {
    function getConstantsValue($name)
    {
        return config('constants.' . $name);
    }
}

if (!function_exists('browser')) {
    function browser()
    {
        $agent = new \Jenssegers\Agent\Agent();
        return $agent->browser() ?? '';
    }
}

if (!function_exists('is_mobile')) {
    function is_mobile()
    {
        $agent = new \Jenssegers\Agent\Agent();
        if ($agent->isMobile()) {
            return true;
        }
        return false;
    }
}

if (!function_exists('getHaversineDistance')) {
    function getHaversineDistance($lat1, $lon1, $lat2, $lon2)
    {
        return '(6371000 * 2 * ASIN(SQRT(POWER(SIN(RADIANS(' . $lat2 . ' - ' . $lat1 . ') / 2), 2) + COS(RADIANS(' . $lat1 . ')) * COS(RADIANS(' . $lat2 . ')) * POWER(SIN(RADIANS(' . $lon2 . ' - ' . $lon1 . ') / 2), 2))))';
    }
}

if (!function_exists('getRandomNumbers')) {
    function getRandomNumbers($length = 6)
    {
        $characters = '0123456789';
        $string = '';

        for ($i = 0; $i < $length; $i++) {
            $string .= $characters[mt_rand(0, strlen($characters) - 1)];
        }

        return $string;
    }
}

/**
 * Retrieve all countries.
 */
if (!function_exists('countries')) {
    function countries()
    {
        return \App\Models\Country::all();
    }
}

/**
 * Return all locales.
 */
if (!function_exists('getAllLocales')) {
    function getAllLocales()
    {
        return \App\Models\Language::where('status', 1)->all();
    }
}

/**
 * Returns all currencies.
 */
if (!function_exists('getAllCurrencies')) {
    function getAllCurrencies()
    {
        return \App\Models\Currency::where('status', 1)->all();
    }
}

/**
 * Returns tafqeet.
 */
// if (!function_exists('tafqeet')) {
//     function tafqeet($amount, $currencyCode, $locale = '')
//     {
//         if (is_null($amount))
//             $amount = 0;

//         try {
//             if ($locale == 'ar') {
//                 return Tafqeet::inArabic($amount, strtolower($currencyCode));
//                 // return Numbers::TafqeetMoney($price, $currencyCode);
//             }
//             return NumberToWords::transformCurrency(($locale ?? app()->getLocale()), $amount, $currencyCode);
//         }
//         catch (NumberToWordsException $ex) {
//             return false;
//         }
//     }
// }

/**
 * Returns NumberToWords.
 */

// if (!function_exists('nuumberToWords')) {
//     function nuumberToWords($amount, $currencyCode, $locale = '')
//     {
//         if (is_null($amount))
//             $amount = 0;

//         try {
//             return NumberToWords::transformCurrency(($locale ?? app()->getLocale()), $amount, $currencyCode);
//         }
//         catch (NumberToWordsException $ex) {
//             return false;
//         }
//     }
// }

/**
 * Returns base currency model.
 */

if (!function_exists('getBaseCurrency')) {
    function getBaseCurrency()
    {
        $baseCurrency = \App\Models\Currency::where('code', config('app.currency', null))->firstOrFail();

        if (!$baseCurrency) {
            $baseCurrency = \App\Models\Currency::orderBy('id')->firstOrFail();
        }

        return $baseCurrency;
    }
}

/**
 * Returns base currency code.
 */
if (!function_exists('getBaseCurrencyCode')) {
    function getBaseCurrencyCode()
    {
        $baseCurrency = config('app.currency', null);

        if (!$baseCurrency) {
            $baseCurrency = \App\Models\Currency::orderBy('id')->pluck('code')->firstOrFail();
        }

        return $baseCurrency;
    }
}

/**
 * Returns exchange rates.
 *
 * @return object
 */
if (!function_exists('getExchangeRate')) {
    function getExchangeRate($targetCurrencyId)
    {
        return \App\Models\ExchangeRate::where('target_currency', $targetCurrencyId)->first() ?? null;
    }
}

/**
 * Format and convert price with currency symbol.
 *
 * @param float $price
 *
 * @return string
 */
if (!function_exists('currency')) {
    function currency($amount = 0)
    {
        if (is_null($amount)) {
            $amount = 0;
        }

        return formatPrice(convertPrice($amount), getCurrentCurrency()->code);
    }
}

/**
 * Return currency symbol from currency code.
 *
 * @param float $price
 *
 * @return string
 */
if (!function_exists('currencySymbol')) {
    function currencySymbol($code)
    {
        $formatter = new \NumberFormatter(app()->getLocale() . '@currency=' . $code, \NumberFormatter::CURRENCY);

        return $formatter->getSymbol(\NumberFormatter::CURRENCY_SYMBOL);
    }
}

/**
 * Format and convert price with currency symbol.
 *
 * @param float $price
 *
 * @return string
 */
if (!function_exists('formatPrice')) {
    function formatPrice($price, $currencyCode, $locale = '')
    {
        if (is_null($price))
            $price = 0;

        $formatter = new \NumberFormatter($locale ?? app()->getLocale(), \NumberFormatter::CURRENCY);

        return $formatter->formatCurrency((float)$price, $currencyCode);
    }
}

/**
 * Format price with base currency symbol. This method also give ability to encode
 * the base currency symbol and its optional.
 *
 * @param float $price
 * @param bool $isEncoded
 *
 * @return string
 */
if (!function_exists('formatBasePrice')) {
    function formatBasePrice($price, $isEncoded = false)
    {
        if (is_null($price)) {
            $price = 0;
        }

        $formater = new \NumberFormatter(app()->getLocale(), \NumberFormatter::CURRENCY);

        if ($symbol = getBaseCurrency()->symbol) {
            if (currencySymbol(getBaseCurrencyCode()) == $symbol) {
                $content = $formater->formatCurrency((float)$price, getBaseCurrencyCode());
            } else {
                $formater->setSymbol(\NumberFormatter::CURRENCY_SYMBOL, $symbol);

                $content = $formater->format(convertPrice((float)$price));
            }
        } else {
            $content = $formater->formatCurrency((float)$price, getBaseCurrencyCode());
        }

        return !$isEncoded ? $content : htmlentities($content);
    }
}

/**
 * Converts price.
 *
 * @param float $amount
 * @param string $targetCurrencyCode
 * @param string $fromCurrencyCode
 *
 * @return string
 */
if (!function_exists('convertPrice')) {
    function convertPrice($amount, $targetCurrencyCode = null, $fromCurrencyCode = null)
    {
        if (!isset($lastCurrencyCode)) {
            $lastCurrencyCode = getBaseCurrency()->code;
        }

        if ($fromCurrencyCode) {
            if (!isset($lastOrderCode)) {
                $lastOrderCode = $fromCurrencyCode;
            }

            if (($targetCurrencyCode != $lastOrderCode)
                && ($targetCurrencyCode != $fromCurrencyCode)
                && ($fromCurrencyCode != getBaseCurrencyCode())
                && ($fromCurrencyCode != $lastCurrencyCode)
            ) {
                $amount = convertToBasePrice($amount, $fromCurrencyCode);
            }
        }

        $targetCurrency = !$targetCurrencyCode
            ? getBaseCurrency()
            : \App\Models\Currency::where('code', $targetCurrencyCode)->firstOrFail();

        if (!$targetCurrency) {
            return $amount;
        }

        $exchangeRate = getExchangeRate($targetCurrency->id);

        if ('' === $exchangeRate || null === $exchangeRate || !$exchangeRate->rate) {
            return $amount;
        }


        $result = (float)$amount * (float)($lastCurrencyCode == $targetCurrency->code ? 1.0 : $exchangeRate->rate);

        if ($lastCurrencyCode != $targetCurrency->code) {
            $lastCurrencyCode = $targetCurrency->code;
        }

        return $result;
    }
}

/**
 * Converts to base price.
 *
 * @param float $amount
 * @param string $targetCurrencyCode
 *
 * @return string
 */
if (!function_exists('convertToBasePrice')) {
    function convertToBasePrice($amount, $targetCurrencyCode = null)
    {
        $targetCurrency = !$targetCurrencyCode ? getBaseCurrency() : \App\Models\Currency::where('code', $targetCurrencyCode)->firstOrFail();

        if (!$targetCurrency) {
            return $amount;
        }

        $exchangeRate = getExchangeRate($targetCurrency->id);

        if (null === $exchangeRate || !$exchangeRate->rate) {
            return $amount;
        }

        return (float)$amount / $exchangeRate->rate;
    }
}

/**
 * Format and convert price with currency symbol.
 *
 * @return array
 */
if (!function_exists('getAccountJsSymbols')) {
    function getAccountJsSymbols()
    {
        $formater = new \NumberFormatter(app()->getLocale(), \NumberFormatter::CURRENCY);

        $pattern = $formater->getPattern();

        $pattern = str_replace("¤", "%s", $pattern);

        $pattern = str_replace("#,##0.00", "%v", $pattern);

        return [
            'symbol' => currencySymbol(getBaseCurrencyCode()),
            'decimal' => $formater->getSymbol(\NumberFormatter::DECIMAL_SEPARATOR_SYMBOL),
            'format' => $pattern,
        ];
    }
}

/**
 * Check whether sql date is empty.
 *
 * @param string $date
 *
 * @return bool
 */
if (!function_exists('is_empty_date')) {
    function is_empty_date($date)
    {
        return preg_replace('#[ 0:-]#', '', $date) === '';
    }
}

if (!function_exists('get_request_timezone_diff_hours')) {
    function get_request_timezone_diff_hours()
    {
        return data_get(TimezoneEnum::DifHours(), request('timezone', config('app.timezone')), '');
    }
}

if (!function_exists('covertOrderDateToLocalTimeZone')) {
    function covertOrderDateToLocalTimeZone()
    {
        $timezoneDifHours = get_request_timezone_diff_hours();
        return "CONVERT_TZ(order_date, '+00:00', '$timezoneDifHours')";
    }
}

/**
 * Convert date to another timezone using current channel.
 *
 * @param \Illuminate\Support\Carbon|null $date
 * @param string $tz
 *
 * @return  string
 */
if (!function_exists('convertDateToTimezone')) {
    function convertDateToTimezone($date, $fromTz = null, $toTz = null)
    {
        if (is_null($date)) {
            $date = Carbon::now();
        } else {
            $date = Carbon::createFromFormat('Y-m-d H:i:s', $date, $fromTz ?? config('app.timezone'))
                ->setTimezone($toTz ?? config('app.timezone'));
        }

        return $date->toDateTimeString();
    }
}

if (!function_exists('convertDateToRiyadhTimezone')) {
    function convertDateToRiyadhTimezone($date, $fromTz = null)
    {
        if ($date === null) {
            return null; // or any default value you want to return for null dates
        }
        return Carbon::createFromFormat('Y-m-d H:i:s', $date, $fromTz ?? config('app.timezone'))
            ->timezone('Asia/Riyadh')->toDateTimeString();
    }
}

if (!function_exists('convertTimeToTimezone')) {
    function convertLocalTimeToConfiguredTimezone($time, $fromTz = 'UTC', $toTz = null, $format = 'H:i:s')
    {
        if (is_null($time)) {
            $time = Carbon::now()->toTimeString();
        } else {
            $time = Carbon::createFromFormat($format, $time, $fromTz)->setTimezone($toTz ?? config('app.timezone'))->toTimeString();
        }

        return $time;
    }
}

/**
 * Format date using current channel.
 *
 * @param \Illuminate\Support\Carbon|null $date
 * @param string $format
 *
 * @return  string
 */
if (!function_exists('formatDate')) {
    function formatDate($date = null, $format = 'Y-m-d H:i:s')
    {
        if (is_null($date)) {
            $date = Carbon::now();
        } else {
            $date = Carbon::createFromFormat('Y-m-d H:i:s', $date)->setTimezone(config('app.timezone'));
        }

        return $date->format($format);
    }
}

if (!function_exists('isYmdDate')) {
    function isYmdDate($date = null)
    {
        $parsedDate = Carbon::createFromFormat('Y-m-d', $date);
        return $parsedDate && $parsedDate->format('Y-m-d') === $date;
    }
}

/**
 * Returns time intervals.
 *
 * @param \Illuminate\Support\Carbon $startDate
 * @param \Illuminate\Support\Carbon $endDate
 *
 * @return array
 */
if (!function_exists('getTimeInterval')) {
    function getTimeInterval($startDate, $endDate)
    {
        $timeIntervals = [];

        $totalDays = $startDate->diffInDays($endDate) + 1;
        $totalMonths = $startDate->diffInMonths($endDate) + 1;

        $startWeekDay = Carbon::createFromTimeString(xWeekRange($startDate, 0) . ' 00:00:01');
        $endWeekDay = Carbon::createFromTimeString(xWeekRange($endDate, 1) . ' 23:59:59');
        $totalWeeks = $startWeekDay->diffInWeeks($endWeekDay);

        if ($totalMonths > 5) {
            for ($i = 0; $i < $totalMonths; $i++) {
                $date = clone $startDate;
                $date->addMonths($i);

                $start = Carbon::createFromTimeString($date->format('Y-m-d') . ' 00:00:01');
                $end = $totalMonths - 1 == $i
                    ? $endDate
                    : Carbon::createFromTimeString($date->format('Y-m-d') . ' 23:59:59');

                $timeIntervals[] = ['start' => $start, 'end' => $end, 'formatedDate' => $date->format('M')];
            }
        } elseif ($totalWeeks > 6) {
            for ($i = 0; $i < $totalWeeks; $i++) {
                $date = clone $startDate;
                $date->addWeeks($i);

                $start = $i == 0
                    ? $startDate
                    : Carbon::createFromTimeString(xWeekRange($date, 0) . ' 00:00:01');
                $end = $totalWeeks - 1 == $i
                    ? $endDate
                    : Carbon::createFromTimeString(xWeekRange($date, 1) . ' 23:59:59');

                $timeIntervals[] = ['start' => $start, 'end' => $end, 'formatedDate' => $date->format('d M')];
            }
        } else {
            for ($i = 0; $i < $totalDays; $i++) {
                $date = clone $startDate;
                $date->addDays($i);

                $start = Carbon::createFromTimeString($date->format('Y-m-d') . ' 00:00:01');
                $end = Carbon::createFromTimeString($date->format('Y-m-d') . ' 23:59:59');

                $timeIntervals[] = ['start' => $start, 'end' => $end, 'formatedDate' => $date->format('d M')];
            }
        }

        return $timeIntervals;
    }
}

/**
 * @param string $date
 * @param int $day
 *
 * @return string
 */
if (!function_exists('xWeekRange')) {
    function xWeekRange($date, $day)
    {
        $ts = strtotime($date);

        if (!$day) {
            $start = (date('D', $ts) == 'Sun') ? $ts : strtotime('last sunday', $ts);

            return date('Y-m-d', $start);
        } else {
            $end = (date('D', $ts) == 'Sat') ? $ts : strtotime('next saturday', $ts);

            return date('Y-m-d', $end);
        }
    }
}

/**
 * @param array $array1
 * @param array $array2
 *
 * @return array
 */
if (!function_exists('arrayMerge')) {
    function arrayMerge(array &$array1, array &$array2)
    {
        $merged = $array1;

        foreach ($array2 as $key => &$value) {
            if (is_array($value) && isset($merged[$key]) && is_array($merged[$key])) {
                $merged[$key] = arrayMerge($merged[$key], $value);
            } else {
                $merged[$key] = $value;
            }
        }

        return $merged;
    }
}

/**
 * @param int $name
 *
 * @return string
 */
if (!function_exists('get_default_avatar')) {
    function get_default_avatar($name)
    {
        $name = trim(collect(explode(' ', $name))->map(function ($segment) {
            return mb_substr($segment, 0, 1);
        })->join(' '));

        return 'https://ui-avatars.com/api/?name=' . urlencode($name) . '&color=7F9CF5&background=EBF4FF';
    }
}

if (!function_exists('get_locale_name')) {
    function get_locale_name($locale = null)
    {
        // If $locale is not provided, use the current app locale
        $locale = $locale ?? app()->getLocale();

        $localeNames = [
            'en' => __('locale.locale.en'),
            'ar' => __('locale.locale.ar'),
        ];

        return $localeNames[$locale] ?? $locale; // Default to the provided locale if not found
    }
}

if (!function_exists('get_user_permissions')) {
    function get_user_permissions()
    {
        $viewGlobalName = __('locale.user_mangement.roles.ability.view');

        $allPermissionsArray = [
            'view_own' => __('locale.user_mangement.roles.ability.view_own'),
            'view' => $viewGlobalName,
            'create' => __('locale.user_mangement.roles.ability.create'),
            'edit' => __('locale.user_mangement.roles.ability.edit'),
            'delete' => __('locale.user_mangement.roles.ability.delete'),
        ];

        $withoutViewOwnPermissionsArray = [
            'view' => $viewGlobalName,
            'create' => __('locale.user_mangement.roles.ability.create'),
            'edit' => __('locale.user_mangement.roles.ability.edit'),
            'delete' => __('locale.user_mangement.roles.ability.delete'),
        ];

        $withNotApplicableViewOwn = array_merge(['view_own' => ['not_applicable' => true, 'name' => __('locale.user_mangement.roles.permission.view_own')]], $withoutViewOwnPermissionsArray);

        $corePermissions = [

            'business_profile' => [
                'name' => __('locale.user_mangement.roles.acl.business_profile'),
                'capabilities' => $withoutViewOwnPermissionsArray,
            ],

            'shops' => [
                'name' => __('locale.user_mangement.roles.acl.shops'),
                'capabilities' => $withoutViewOwnPermissionsArray,
            ],

            'trips' => [
                'name' => __('locale.user_mangement.roles.acl.trips'),
                'capabilities' => [
                    'view' => $viewGlobalName,
                ]
            ],

            'penalties' => [
                'name' => __('locale.user_mangement.roles.acl.penalties'),
                'capabilities' => [
                    'view' => $viewGlobalName,
                ]
            ],

            'rental_settings' => [
                'name' => __('locale.user_mangement.roles.acl.rental_settings'),
                'capabilities' => $withoutViewOwnPermissionsArray,
            ],

            'bikes' => [
                'name' => __('locale.user_mangement.roles.acl.bikes'),
                'capabilities' => $withoutViewOwnPermissionsArray,
            ],

            'categories' => [
                'name' => __('locale.user_mangement.roles.acl.categories'),
                'capabilities' => $withoutViewOwnPermissionsArray,
            ],

            'customers' => [
                'name' => __('locale.user_mangement.roles.acl.customers'),
                'capabilities' => $withoutViewOwnPermissionsArray,
            ],

            'staffs' => [
                'name' => __('locale.user_mangement.roles.acl.staffs'),
                'capabilities' => $withoutViewOwnPermissionsArray,
            ],

            'roles' => [
                'name' => __('locale.user_mangement.roles.acl.roles'),
                'capabilities' => $withoutViewOwnPermissionsArray,
                'help' => [
                    'delete' => __('locale.user_mangement.roles.acl.help.delete_system_user_roles'),
                ],
            ],

            'permissions' => [
                'name' => __('locale.user_mangement.roles.acl.permissions'),
                'capabilities' => $withoutViewOwnPermissionsArray,
                'help' => [
                    'delete' => __('locale.user_mangement.roles.acl.help.delete_system_user_permissions'),
                ],
            ],

            'currencies' => [
                'name' => __('locale.user_mangement.roles.acl.currencies'),
                'capabilities' => $withoutViewOwnPermissionsArray,
            ],

            'exchange_rates' => [
                'name' => __('locale.user_mangement.roles.acl.exchange_rates'),
                'capabilities' => $withoutViewOwnPermissionsArray,
            ],

            'locales' => [
                'name' => __('locale.user_mangement.roles.acl.locales'),
                'capabilities' => $withoutViewOwnPermissionsArray,
            ]
        ];

        return $corePermissions;
    }

    if (!function_exists('localized_attribute')) {
        function localized_attribute($model, $attribute)
        {
            $locale = app()->getLocale();
            return $locale === 'ar' ? $model->{$attribute . '_ar'} : $model->{$attribute . '_en'};
        }
    }

    /**
     * generate a random string with specified requirements.
     *
     * @param int $length Length of the random string (default is 7)
     * @return string Generated random string
     */
    if (!function_exists('generateRandomString')) {
        function generateRandomString($length = 10)
        {
            $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
            $charactersLength = strlen($characters);
            $randomString = '#';
            for ($i = 0; $i < $length - 1; $i++) {
                $randomString .= $characters[rand(0, $charactersLength - 1)];
            }
            return $randomString;
        }
    }

    if (!function_exists('getOrderStatusColor')) {
        function getOrderStatusColor(string $status): string
        {
            return match ($status) {
                'received' => '#ADD8E6',         // Light Blue
                'processing' => '#FFEB3B',       // Bright Yellow
                'finished' => '#4CAF50',         // Medium Green
                'delivering', 'delivered' => '#2E7D32',  // Medium Green, Dark Green
                'canceled' => '#FF5733',        // Crimson Red
                'pending_payment' => '#00ffc2',  // Cyan
                default => '#9E9E9E'             // Gray
            };
        }
    }

    if (!function_exists('getLastYear')) {
        function getLastYear()
        {
            return \Carbon\Carbon::now()->subYear()->year;
        }
    }

}
