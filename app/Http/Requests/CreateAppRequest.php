<?php

namespace App\Http\Requests;

use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

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
            'display_name' => ['sometimes', 'max:100', Rule::unique('apps', 'display_name')->ignore($this->route('app')->display_name ?? '', 'display_name')],
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
            'team_id.sometimes' => 'Please provide a team for your app.',
            'display_name.unique' => "App name ' $this->display_name ' exists already, try with a different name."
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
