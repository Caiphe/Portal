<?php

namespace App\Http\Requests;

use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\FormRequest;

class CreateAppRequest extends FormRequest {
	/**
	 * Determine if the user is authorized to make this request.
	 *
	 * @return bool
	 */
	public function authorize() {
		return Auth::check();
	}

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules() {
		return [
		    'app_owner' => 'sometimes',
			'display_name' => 'sometimes|max:100',
			'url' => 'sometimes',
			'description' => 'sometimes',
			'country' => 'sometimes',
			'products' => 'required|array|min:1',
            'team_id' => 'sometimes'
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
            'display_name' => filter_var($this->display_name, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES),
            'url' => filter_var($this->url, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES),
            'description' => filter_var($this->description, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES),
        ]);
    }

    public function messages()
    {
        return [
            'products.required' => 'Please select at least one product.',
            'team_id.sometimes' => 'Please provide a team for your app.'
        ];
    }
}
