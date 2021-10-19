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
            'name' => 'required|unique:teams,name',
            'url' => 'required',
            'contact' => 'required',
            'country' => 'required',
            'logo_file' => 'sometimes',
            'team_members' => 'sometimes',
            'description' => 'required',
        ];
    }

    /**
     * Prepare the data for validation.
     *s
     * @return void
     */
    protected function prepareForValidation()
    {
        $this->merge([
            'name' => filter_var($this->name, FILTER_SANITIZE_STRING),
            'url' => filter_var($this->url, FILTER_SANITIZE_STRING),
            'contact' => filter_var($this->contact, FILTER_SANITIZE_STRING),
            'country' => filter_var($this->country, FILTER_SANITIZE_EMAIL),
            'logo_file' => filter_var($this->logo_file, FILTER_SANITIZE_STRING),
            'description' => filter_var($this->description, FILTER_SANITIZE_STRING),
        ]);
    }
}
