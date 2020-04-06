<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class CreateAppRequest extends FormRequest
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
            'key' => 'sometimes',
            'name' => 'required',
            'new_name' => 'sometimes',
            'url' => 'sometimes',
            'description' => 'sometimes',
            'products' => 'required:array',
            'original_products' => 'sometimes:array'
        ];
    }
}
