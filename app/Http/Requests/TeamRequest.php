<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TeamRequest extends FormRequest
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
            'name' => 'required',
            'url' => 'required',
            'contact' => 'required',
            'country' => 'required|email:rfc,dns',
            'logo' => 'required',
            'invitation' => 'required',
            'description' => 'required',
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
            'name' => filter_var($this->name, FILTER_SANITIZE_STRING),
            'url' => filter_var($this->url, FILTER_SANITIZE_STRING),
            'contact' => filter_var($this->contact, FILTER_SANITIZE_STRING),
            'country' => filter_var($this->country, FILTER_SANITIZE_EMAIL),
            'logo' => filter_var($this->logo, FILTER_SANITIZE_STRING),
            'invitation' => filter_var($this->required, FILTER_SANITIZE_STRING),
            'description' => filter_var($this->required, FILTER_SANITIZE_STRING),
        ]);
    }
}
