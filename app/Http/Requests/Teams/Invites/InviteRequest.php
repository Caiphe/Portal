<?php

namespace App\Http\Requests\Teams\Invites;

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
            'team_id' => 'required',
            'invitee' => 'required',
            'type' => 'sometimes',
        ];
    }
}
