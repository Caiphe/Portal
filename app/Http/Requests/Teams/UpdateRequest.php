<?php

namespace App\Http\Requests\Teams;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class UpdateRequest
 *
 * @package App\Http\Requests\Teams
 */
class UpdateRequest extends FormRequest
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
            'country' => 'required',
            'logo_file' => 'sometimes|file',
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
            'description' => filter_var($this->description, FILTER_SANITIZE_STRING),
        ]);
    }
}
