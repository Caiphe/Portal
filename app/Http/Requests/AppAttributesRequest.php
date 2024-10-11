<?php

namespace App\Http\Requests;

use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\FormRequest;

class AppAttributesRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'attribute' => ['sometimes', 'array', 'max:18'], // Max 18 elements
            'attribute.name' => ['sometimes', 'string', 'max:1024'], // Max 1 KB
            'attribute.value' => ['sometimes', 'string', 'max:2048'], // Max 2 KB
        ];
    }

    /**
     * Custom messages for validation errors.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'attribute.array' => 'The attribute must be an array.',
            'attribute.max' => 'The attribute array must not have more than 18 elements.',
            'attribute.*.name.max' => 'The name must not exceed 1 KB.',
            'attribute.*.value.max' => 'The value must not exceed 2 KB.',
        ];
    }




}
