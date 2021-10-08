<?php

namespace App\Http\Requests\Teams;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class InviteRequest
 *
 * @package App\Http\Requests\Teams
 */
class InviteRequest extends FormRequest
{
    public function authorize() {
        return true;
    }

    public function rules() {
        return [
            'invitee' => 'required',
            'type' => 'required',
            'team_id' => 'required',
        ];
    }
}
