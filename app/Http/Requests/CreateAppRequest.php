<?php

namespace App\Http\Requests;

use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\FormRequest;

class CreateAppRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'app_owner' => ['sometimes'],
            'display_name' => ['sometimes', 'max:100'],
            'url' => ['sometimes'],
            'description' => ['sometimes'],
            'country' => ['sometimes'],
            'products' => ['required', 'array', 'min:1'],
            'team_id' => ['sometimes'],
            'attribute' => ['sometimes', 'array'],
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
            'display_name' => htmlspecialchars($this->display_name),
            'url' => htmlspecialchars($this->url),
            'description' => htmlspecialchars($this->description),
            'attribute' => $this->sanitizeAttributes($this->attribute ?? []),
        ]);
    }

    public function messages()
    {
        return [
            'products.required' => 'Please select at least one product.',
            'team_id.sometimes' => 'Please provide a team for your app.'
        ];
    }

    protected function sanitizeAttributes(array $attributes): array
    {
        $attrs = [];
        foreach ($attributes as $attr) {
            $attrs[] = [
                "name" => htmlspecialchars($attr["name"]),
                'value' => htmlspecialchars($attr["value"])
            ];
        }
        return $attrs;
    }
}
