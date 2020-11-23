<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class KycRequest extends FormRequest
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
            'national_id' => 'required',
            'number' => 'required',
            'email' => 'required|email:rfc,dns',
            'business_name' => 'required',
            'business_type' => 'required',
            'business_description' => 'required',
            'files' => 'required',
            'accept' => 'required|accepted',
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
            'national_id' => filter_var($this->national_id, FILTER_SANITIZE_STRING),
            'number' => filter_var($this->number, FILTER_SANITIZE_STRING),
            'email' => filter_var($this->email, FILTER_SANITIZE_EMAIL),
            'business_name' => filter_var($this->business_name, FILTER_SANITIZE_STRING),
            'business_type' => filter_var($this->business_type, FILTER_SANITIZE_STRING),
            'business_description' => filter_var($this->business_description, FILTER_SANITIZE_STRING),
        ]);
    }
}
