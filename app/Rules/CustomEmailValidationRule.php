<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class CustomEmailValidationRule implements Rule
{
    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value): bool
    {
        // Regular expression to validate email with '#'pRe
        return preg_match('/^[a-zA-Z0-9_.+-]+@[a-zA-Z0-9-]+\.[a-zA-Z0-9-.]+$/', $value);
    }

    public function message(): string
    {
        return 'The :attribute format is invalid.';
    }
}
