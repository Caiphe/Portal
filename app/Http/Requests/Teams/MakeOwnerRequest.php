<?php

namespace App\Http\Requests\Teams;

use Illuminate\Foundation\Http\FormRequest;

class MakeOwnerRequest extends FormRequest
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

    public function rules() {
        return [
            'user_email' => 'required',
            'team_id' => 'required'
        ];
    }

    public function messages()
    {
        return [
            'user_email' => 'The user to make owner of the Team is required.',
            'team_id' => 'The team required.'
        ];
    }
}
