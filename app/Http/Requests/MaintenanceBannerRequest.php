<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MaintenanceBannerRequest extends FormRequest
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

    public function rules()
    {
        return [
            'message' => ['required', 'string'],
            'enabled' => ['required'],
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'message' => htmlspecialchars($this->message),
        ]);
    }

    public function messages()
    {
        return [
            'message.required' => 'Banner message required.',
            'enabled.required' => 'Banner option required.'
        ];
    }
}
