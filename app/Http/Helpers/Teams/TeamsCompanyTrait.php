<?php

namespace App\Http\Helpers\Teams;

use App\Services\ApigeeService;
use App\Team;
use App\User;

trait TeamsCompanyTrait
{
    public function storeTeam($data)
    {
        $owner = User::where('email', $data->team_members[0])->first();
        //Get team owner email from APIGEE
        $user = ApigeeService::get('developers/' . $owner->email);

        if(isset($user['code'])){
            return redirect()->back()->with('alert', "error:'" . $user['message'] . "'");
        }

        $data['name'] = preg_replace('/[-_±§@#$%^&*()+=!]+/', '', $data['name']);

        $data['logo'] = $this->processLogoFile($data);

        $teamCount = Team::where('owner_id', $owner->id)
            ->where('created_at', '>=', now()->startOfDay())
            ->count();

        if($teamCount >= 2){
            return response()->json(['success' => true], 429);
        }
//dd($data->request->all());
        $team = $this->createTeam($owner, $data->request->all());
        dd($team);
        if (!empty($data['team_members'])) {
            $teamInviteEmails = explode(',', $data['team_members']);
            $this->sendInvites($teamInviteEmails, $team);
        }

        if($team){
            return response()->json(['success' => true], 200);
        }
    }
}
