<?php

namespace App\Http\Requests\Teams;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class Request
 * s
 * @package App\Http\Requests\Teams
 */
class Request extends FormRequest
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
            'logo_file' => 'required|file|max:5120|dimensions:max_width=2000,max_height=2000',
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
            'country' => filter_var($this->country, FILTER_SANITIZE_STRING),
            'description' => filter_var($this->description, FILTER_SANITIZE_STRING),
        ]);
    }

    public function messages()
    {
        return [
            'logo_file.max' => 'The logo file size is more than the allowed 5MB limit',
            'logo_file.dimensions' => 'The logo dimentions are too large, please make sure the width and height are less than 2000'
        ];
    }
}
