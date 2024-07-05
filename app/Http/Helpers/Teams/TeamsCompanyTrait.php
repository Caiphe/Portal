<?php

namespace App\Http\Helpers\Teams;

trait TeamsCompanyTrait
{
    public function storeTeam($data)
    {
        dd($data);

        $user = $request->user();
        $data = $request->validated();
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
