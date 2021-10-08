<?php

namespace App\Http\Requests\Teams;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class LeaveTeamRequest
 *
 * @package App\Http\Requests\Teams
 */
class LeaveTeamRequest extends FormRequest
{
    public function authorize() {
        return true;
    }

    public function rules() {
        return [
            'user_id' => 'required',
            'team_id' => 'required'
        ];
    }

    public function messages()
    {
        return [
            'user_id' => 'The user leaving the Team is required.',
            'team_id' => 'The team the user is leaving is required.'
        ];
    }
}
