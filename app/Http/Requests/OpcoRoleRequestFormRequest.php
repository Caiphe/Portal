<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OpcoRoleRequestFormRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'message' => ['required', 'max:500'],
            'countries' => ['required'],
            'status' => ['nullable']
        ];
    }

    public function prepareForValidation()
    {
        $this->merge([
            'message' => htmlspecialchars($this->message, ENT_NOQUOTES),
        ]);
    }
}
