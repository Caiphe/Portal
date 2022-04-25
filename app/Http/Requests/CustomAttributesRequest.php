<?php

namespace App\Http\Requests;

use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\FormRequest;

class CustomAttributesRequest extends FormRequest
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
            'attribute' => $this->sanitizeAttributes($this->attribute ?? []),
        ]);
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
