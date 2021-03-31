<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

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
			'key' => 'sometimes',
			'name' => 'required',
			'new_name' => 'sometimes',
			'url' => 'sometimes',
			'description' => 'sometimes',
			'country' => 'sometimes',
			'products' => 'required:array',
			'original_products' => 'sometimes:array',
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
            'name' => filter_var($this->name, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES),
            'new_name' => filter_var($this->new_name, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES),
            'url' => filter_var($this->url, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES),
            'description' => filter_var($this->description, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES),
        ]);
    }
}
