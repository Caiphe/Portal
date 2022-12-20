<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
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
            'category_cid' => ['required'],
            'group' => ['nullable'],
            'tab' => ['array'],
        ];
    }

    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        $tab = $this->tab;
        $tab['body'] = array_map(fn ($body) => strip_tags($body, '<strong><a><em><i><del><img><ul><ol><li><pre><br><p><table><tbody><thead><tr><td><h2><h3><h4><s><blockquote><u>'), $tab['body']);

        $this->merge([
            'locations' => array_map(fn($item) =>  htmlspecialchars($item, ENT_NOQUOTES), $this->locations ?? []),
            'group' => htmlspecialchars($this->group, ENT_NOQUOTES),
            'category_cid' => htmlspecialchars($this->category_cid, ENT_NOQUOTES),
            'tab' => $tab,
        ]);
    }
}
