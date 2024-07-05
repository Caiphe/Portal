<?php

namespace App\Http\Helpers\Teams;

use App\Services\ApigeeService;
use App\User;

trait TeamsCompanyTrait
{
    public function storeTeam($data)
    {
        dd($data);

        $owner = User::where('email', $data->team_owner)->first();
        //Get team owner email from APIGEE
        $user = ApigeeService::get('developers/' . $owner->email);
        $user->load(['responsibleCountries']);

        $data['name'] = preg_replace('/[-_±§@#$%^&*()+=!]+/', '', $data['name']);

        $data['logo'] = $this->processLogoFile($request);

        $teamCount = Team::where('owner_id', $user->id)
            ->where('created_at', '>=', now()->startOfDay())
            ->count();

        if($teamCount >= 2){
            return response()->json(['success' => true], 429);
        }

        $team = $this->createTeam($user, $data);

        if (!empty($data['team_members'])) {
            $teamInviteEmails = explode(',', $data['team_members']);
            $this->sendInvites($teamInviteEmails, $team);
        }

        if($team){
            return response()->json(['success' => true], 200);
        }
    }
}
