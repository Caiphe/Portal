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
            'name' => 'sometimes',
            'member' => 'sometimes',
            'team_id' => 'required'
        ];
    }
}
