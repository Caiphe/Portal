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
            'display_name' => ['required', 'max:100'],
            'url' => ['sometimes'],
            'description' => ['sometimes'],
            'country' => ['sometimes'],
            'products' => ['required', 'array', 'min:1'],
            'team_id' => ['sometimes'],
            'attribute' => ['sometimes', 'array'],
            'entity_name' => ['required', 'string'],
            'channels' => ['required', 'array', 'min:1'],
            'contact_number' => ['required', 'string'],
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
            'entity_name' => htmlspecialchars($this->entity_name),
            'contact_number' => htmlspecialchars($this->contact_number),
            'description' => htmlspecialchars($this->description),
            'attribute' => $this->sanitizeAttributes($this->attribute ?? []),
        ]);
    }

    public function messages()
    {
        return [
            'products.required' => 'Please select at least one product.',
            'channels.required' => 'Please select at least one channel for your app.',
            'team_id.sometimes' => 'Please provide a team for your app.',
            'contact_number.required' => 'Please provide a contact number.',
            'display_name.required' => 'Please provide a name for your app.',
        ];
    }

    protected function sanitizeAttributes(array $attributes): array
    {
        $attrs = [];
        foreach ($attributes as $attr) {
            $attrs[] = [
                "name" => htmlspecialchars($attr["name"], ENT_NOQUOTES),
                'value' => htmlspecialchars($attr["value"], ENT_NOQUOTES)
            ];
        }
        return $attrs;
    }
}
