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
    public static function listFunc(string $field, mixed $model): string
    {
        $options = explode(',', $field, 2);
        $value = Arr::get($model, $options[0]);

        if (count($options) === 1) {
            return $value;
        }

        $actions = explode(':', $options[1]);
        $methodName = $actions[0];

        return self::$methodName($value, $actions[1] ?? null);
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

    public static function date($date, ?string $format): string
    {
        return date($format ?? 'Y-m-d', strtotime($date));
    }

    public static function implode($arr, ?string $options): string
    {
        $options = explode('|', $options);
        if ($arr instanceof Collection) {
            return $arr->implode($options[1], $options[0]);
        }

        return implode($options[0], $arr);
    }
}
