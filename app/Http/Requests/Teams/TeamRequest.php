<?php

namespace App\Http\Requests\Teams;

use Illuminate\Foundation\Http\FormRequest;

class TeamRequest extends FormRequest
{
    public function authorize() {
        return true;
    }

    public function rules() {
        return [
            'team_id' => 'reaquired',
        ];
    }
}
