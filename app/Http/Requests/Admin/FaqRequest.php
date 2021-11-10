<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class FaqRequest extends FormRequest {

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize() {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules() {
        return [
            'question' => 'required|string',
            'answer' => 'required|string',
            'category_cid' => 'required'
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
            'question' => filter_var($this->question, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES),
            'answer' => strip_tags($this->answer, '<strong><a><em><del><img><ul><ol><li><pre><br><p><table><h2><h3><s><blockquote><u>'),
            'category_cid' => filter_var($this->category_cid, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES),
        ]);
    }
}
