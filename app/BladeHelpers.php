<?php

namespace App;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

/**
 * Blade helpers
 */
class BladeHelpers
{
    public static function listFunc(string $field, $model): string
    {
        $options = explode('|', $field);
        $value = Arr::get($model, $options[0]);

        if (count($options) === 1) {
            return $value;
        }

        foreach (array_slice($options, 1) as $option) {
            $actions = explode(':', $option);
            $methodName = $actions[0];
            if (isset($actions[1])) {
                $value = self::$methodName($value, $actions[1]);
            } else {
                $value = self::$methodName($value);
            }
        }

        return $value;
    }

    public static function splitToTag($values, ?string $delimiter = ','): string
    {
        $tags = "";
        $className = "";

        if (is_string($values)) {
            $values = explode($delimiter ?? ',', $values);
        }

        foreach ($values as $value) {
            $className = Str::slug($value);
            $tags .= "<span class=\"$className\">{$value}</span>";
        }

        return $tags;
    }

    public static function date($date, ?string $format = 'Y-m-d'): string
    {
        return date($format, strtotime($date));
    }

    public static function implode($arr, ?string $options): string
    {
        $options = explode('>', $options);
        if ($arr instanceof Collection) {
            return $arr->implode($options[1], $options[0]);
        }

        return implode($options[0], $arr);
    }

    public static function strToUpper(string $str): string
    {
        return strtoupper($str);
    }
}
