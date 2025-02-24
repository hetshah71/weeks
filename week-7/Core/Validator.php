<?php

namespace Core;
use DateTime;
class Validator
{
    public static function string($val, $min = 1, $max = INF)
    {
        $val = trim($val);
        return strlen($val) >= $min && strlen($val) <= $max;
    }
    public static function email($val)
    {
        return filter_var($val, FILTER_VALIDATE_EMAIL);
    }
    public static function validateAmount($val)
    {
        return !empty($val) && is_numeric($val) && $val > 0;
    }
    public static function validateDate($date)
    {
        return !empty($date) && DateTime::createFromFormat('Y-m-d', $date) !== false;
    }
}
