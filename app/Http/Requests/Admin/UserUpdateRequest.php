<?php

namespace App\Http\Requests\Admin;

use Illuminate\Validation\Rule;
use App\Rules\CustomEmailValidationRule;
use Illuminate\Validation\Rules\Password;
use Illuminate\Foundation\Http\FormRequest;

class UserUpdateRequest extends FormRequest
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
            'first_name' => ['required', 'max:140'],
            'last_name' => ['required', 'max:140'],
            'password' => [
                'sometimes',
                'nullable',
                'confirmed',
                Password::min(12)
                ->letters()
                ->mixedCase()
                ->numbers()
                ->symbols()
            ],
            'roles' => ['array'],
            'country' => ['required', 'array'],
            'responsible_countries' => ['nullable', 'array', Rule::requiredIf(in_array(3, $this->roles))],
            'responsible_groups' => ['nullable', 'array'],
            'private_products' => ['nullable', 'array'],
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'first_name' => htmlspecialchars($this->first_name, ENT_NOQUOTES),
            'last_name' => htmlspecialchars($this->last_name, ENT_NOQUOTES),
            'roles' => array_map(fn($item) =>  htmlspecialchars($item, ENT_NOQUOTES), $this->roles ?? []),
            'country' => array_map(fn($item) =>  htmlspecialchars($item, ENT_NOQUOTES), $this->country ?? []),
            'responsible_countries' => array_map(fn($item) =>  htmlspecialchars($item, ENT_NOQUOTES), $this->responsible_countries ?? []),
            'responsible_groups' => array_map(fn($item) =>  htmlspecialchars($item, ENT_NOQUOTES), $this->responsible_groups ?? []),
            'private_products' => array_map(fn($item) =>  htmlspecialchars($item, ENT_NOQUOTES), $this->private_products ?? []),
        ]);
    }

    public function messages()
    {
        return [
            'responsible_countries.required' => 'Please select at least one country this Opco admin is responsible for.',
            'country.required' => 'Please select a country of operation.',
        ];
    }
}
