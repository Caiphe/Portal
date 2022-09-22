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
    /**
     * Run functions on items.
     * eg: roles|implode:, >label.
     * `roles` is the $model field to look for.
     * `|` is to indicate piping the value to a method. * Multple `|` can be used together. eg, roles|implode:, >label|strToUpper.
     * `implode` is the method to run.
     * `:` and anything after this are the * arguments to pass. Multplie `:` can be used to pass multiple arguments
     *
     * @param      string            $field  The field
     * @param      array|collection  $model  The model
     *
     * @return     string            A new string formatted by the functions run.
     */
    public static function listFunc(string $field, $model): string
    {
        $options = explode('|', $field);
        $value = Arr::get($model, $options[0]);

        if (is_string($value)) {
            $value = htmlspecialchars($value, ENT_QUOTES);
        }

        if (count($options) === 1) {
            return $value;
        }

        foreach (array_slice($options, 1) as $option) {
            $actions = explode(':', $option);
            $methodName = $actions[0];
            if (count($actions) > 1) {
                $value = self::$methodName($value, ...array_slice($actions, 1));
            } else {
                $value = self::$methodName($value);
            }
        }

        return $value;
    }

    /**
     * Splits string to HTML tag.
     *
     * @param      string|array  $values     The values
     * @param      string        $delimiter  The delimiter
     *
     * @return     string        Returns span tags built from the values
     */
    public static function splitToTag($values, string $delimiter = ','): string
    {
        $tags = "";
        $className = "";

        if (is_string($values)) {
            $values = explode($delimiter, $values);
        }

        foreach ($values as $value) {
            $className = Str::slug($value);
            $tags .= "<span class=\"$className\">{$value}</span>";
        }

        return $tags;
    }

    /**
     * Formats date
     *
     * @param      string  $date    The date.
     * @param      string  $format  The format.
     *
     * @return     string  The formatted date.
     */
    public static function date(string $date, string $format = 'Y-m-d'): string
    {
        return date($format, strtotime($date));
    }

    /**
     * Implodes an array and converts it to a string.
     *
     * @param      array|collection  $arr      The arr.
     * @param      string            $options  The options.
     *
     * @return     string            The string.
     */
    public static function implode($arr, string $options): string
    {
        $options = explode('>', $options);
        if ($arr instanceof Collection) {
            return $arr->implode($options[1], $options[0]);
        }

        return implode($options[0], $arr);
    }

    /**
     * Converts a string to uppercase
     *
     * @param      string  $str    The string.
     *
     * @return     string  The uppercase version of the string.
     */
    public static function strToUpper(string $str): string
    {
        return strtoupper($str);
    }

    public static function addClass(?string $str = ''): string
    {
        return $str ?? '';
    }
}
