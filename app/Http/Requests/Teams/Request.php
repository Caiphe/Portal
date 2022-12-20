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
            'name' => 'required|unique:teams,name|max:100',
            'url' => 'sometimes|max:100',
            'contact' => 'sometimes|max:16',
            'country' => 'required',
            'logo_file' => 'sometimes|file|max:5120|dimensions:max_width=2000,max_height=2000|mimes:jpeg,jpg,png',
            'team_members' => 'sometimes',
            'description' => 'sometimes|max:512',
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
            'name' => htmlspecialchars($this->name, ENT_NOQUOTES),
            'url' => htmlspecialchars($this->url, ENT_NOQUOTES),
            'contact' => htmlspecialchars($this->contact, ENT_NOQUOTES),
            'country' => htmlspecialchars($this->country, ENT_NOQUOTES),
            'description' => htmlspecialchars($this->description, ENT_NOQUOTES),
        ]);
    }

    public function messages()
    {
        return [
            'logo_file.max' => 'The logo file size is more than the allowed 5MB limit',
            'logo_file.dimensions' => 'The logo dimentions are too large, please make sure the width and height are less than 2000',
        ];
    }
}
