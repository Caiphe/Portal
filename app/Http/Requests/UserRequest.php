<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use App\Rules\CustomEmailValidationRule;
use Illuminate\Validation\Rules\Password;
use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => [
                'required',
                'email:rfc,dns', 
                new CustomEmailValidationRule,
                Rule::unique('users')->ignore(auth()->id())
            ],
            'password' => [
                'nullable',
                'confirmed',
                Password::min(12)
                    ->letters()
                    ->mixedCase()
                    ->numbers()
                    ->symbols()
            ],
            'locations' => 'sometimes|array',
        ];
    }

    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        $this->merge([
            'first_name' => filter_var($this->first_name, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES),
            'last_name' => filter_var($this->last_name, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES),
            'email' => filter_var($this->email, FILTER_SANITIZE_EMAIL),
            'locations' => array_map(fn ($location) => filter_var($location, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES), $this->locations ?? []),
        ]);
    }
}
