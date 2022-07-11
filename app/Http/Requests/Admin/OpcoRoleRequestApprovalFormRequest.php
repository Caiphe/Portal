<?php

namespace App\Http\Requests\Admin;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class OpcoRoleRequestApprovalFormRequest extends FormRequest
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
            'request_id' => ['required']
        ];
    }

    public function prepareForValidation()
    {
        $this->merge([
            'message' => htmlspecialchars($this->message, ENT_NOQUOTES),
        ]);
    }
}
