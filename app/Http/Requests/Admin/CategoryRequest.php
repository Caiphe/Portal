<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CategoryRequest extends FormRequest
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
            'heading-title' => 'required'
        ];
    }

    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        $tags = '<strong><a><em><i><del><img><ul><ol><li><pre><br><p><table><tbody><thead><tr><td><h2><h3><h4><s><blockquote><u>';
        $this->merge([
            'heading-title' => filter_var($this->get('heading-title'), FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES),
            'heading-body' => filter_var($this->get('heading-body'), FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES),
            'benefits-body' => strip_tags($this->get('benefits-body'), $tags),
            'developer-centric-body' => strip_tags($this->get('developer-centric-body'), $tags),
            'bundles-body' => strip_tags($this->get('bundles-body'), $tags),
            'products-body' => strip_tags($this->get('products-body'), $tags),
        ]);
    }
}
