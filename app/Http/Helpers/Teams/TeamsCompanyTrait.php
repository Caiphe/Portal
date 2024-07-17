<?php

namespace App\Http\Helpers\Teams;

use App\Notification;
use App\Services\ApigeeService;
use App\Team;
use App\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;

trait TeamsCompanyTrait
{
    /**
     * @param $data
     * @return JsonResponse|RedirectResponse|void
     */
    public function storeTeam($data)
    {
        // If it's a string, explode it
        $emails = explode(',', $data->input('emails'));

        //Get first team owner email from Portal and check if it exists in APIGEE
        $owner = User::where('email', $emails[0])->first();
        $user = ApigeeService::get('developers/' . $owner->email);

        //abort action if user does not exist in APIGEE
        if (isset($user['code'])) {
            return redirect()->back()->with('alert', "error:'" . $user['message'] . "'");
        }

        $data['name'] = preg_replace('/[-_±§@#$%^&*()+=!]+/', '', $data['name']);

        $data['logo'] = $this->processLogoFile($data);

        $teamCount = Team::where('owner_id', $owner->id)
            ->where('created_at', '>=', now()->startOfDay())
            ->count();

        if ($teamCount >= 2) {
            return response()->json(['success' => true], 429);
        }

        $team = $this->createTeam($owner, $data->request->all());

        if (!empty($data['emails'])) {
            $teamInviteEmails = $emails;

            $this->sendInvites($teamInviteEmails, $team);
        }

        if ($team) {
            return response()->json(['success' => true], 200);
        }
    }


    /**
     * @param $team
     * @param $data
     * @return JsonResponse|RedirectResponse|void
     */
    public function updateTeam($team, $data)
    {
        $company = Team::findOrFail($team['id']);

        $teamLogo = $team->logo;
        $oldName = $team->name;

        $team_owner = User::where('id', $data['team_owner'])->first();

        if (!$team_owner){
            return redirect()->back()->with('alert', "error: user does not exist");
        }

        //Check if the team owner exists in APIGEE
        $apigee_user = ApigeeService::get('developers/' . $team_owner['email']);

        //abort action if user does not exist in APIGEE
        if (isset($apigee_user['code'])) {
            return redirect()->back()->with('alert', "error:'" . $apigee_user['message'] . "'");
        }

        $teamAdmin = $team_owner->hasTeamRole($team, 'team_admin');

        if (!$teamAdmin) {
            return response()->json(['success' => true], 424);
        }

        if ($data->has('logo_file')) {
            $teamLogo = $this->processLogoFile($data);
        }

        $data['name'] = preg_replace('/[-_±§@#$%^&*()+=!]+/', '', $data['name']);

        $company->update([
            'name' => $data['name'],
            'url' => $data['url'],
            'contact' => $data['contact'],
            'country' => $data['country'],
            'logo' => $teamLogo,
            'description' => $data['description'],
        ]);

        $companyUpdated = Team::where('id', $company->id)->first();

        //TODO fix team update API
        ApigeeService::updateCompany($companyUpdated, $team_owner);

        foreach ($companyUpdated->users as $user) {
            if ($oldName !== $companyUpdated->name) {
                Notification::create([
                    'user_id' => $user->id,
                    'notification' => "Your team <strong> {$oldName} </strong> has been updated to <strong>{$companyUpdated->name}</strong>, please click <a href='/teams/{$companyUpdated->id}/team'>here</a> to navigate to your team to view the changes",
                ]);
            } else {
                Notification::create([
                    'user_id' => $user->id,
                    'notification' => "Your team <strong>{$companyUpdated->name}</strong> has been updated please click <a href='/teams/{$companyUpdated->id}/team'>here</a> to navigate to your team to view the changes",
                ]);
            }
        }


        return response()->json(['success' => true], 200);
    }
}
