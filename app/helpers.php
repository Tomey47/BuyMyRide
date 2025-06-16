<?php
if (!function_exists('convert_currency')) {
    function convert_currency($amount, $from = 'EUR', $to = null)
    {
        $to = $to ?: session('currency', 'EUR');
        $rates = config('currencies.rates');
        if (!isset($rates[$from]) || !isset($rates[$to])) {
            return $amount;
        }
        return $amount * ($rates[$to] / $rates[$from]);
    }

    function currency_symbol($currency = null)
    {
        $currency = $currency ?: session('currency', 'EUR');
        return config('currencies.symbols.' . $currency, $currency);
    }
}