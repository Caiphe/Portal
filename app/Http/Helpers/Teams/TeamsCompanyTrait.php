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

        if (!empty($data['team_members'])) {
            $teamInviteEmails = explode(',', $emails);
            $this->sendInvites($teamInviteEmails, $team);
        }

        if ($team) {
            return response()->json(['success' => true], 200);
        }
    }

    /**
     * @param $data
     * @param $id
     * @return JsonResponse
     */
    public function updateTeam($data, $id)
    {
        $team = Team::findOrFail($id);
        $teamLogo = $team->logo;

        $oldName = $team->name;

        $teamOwner = User::where('id', $team->owner_id)->first();
        $teamAdmin = $teamOwner->hasTeamRole($team, 'team_admin');

        if (!$teamAdmin) {
            return response()->json(['success' => true], 424);
        }

        if ($data->has('logo')) {
            $teamLogo = $this->processLogoFile($data);
        }

        $data['name'] = preg_replace('/[-_±§@#$%^&*()+=!]+/', '', $data['name']);

        $team->update([
            'name' => $data['name'],
            'url' => $data['url'],
            'contact' => $data['contact'],
            'country' => $data['country'],
            'logo' => $teamLogo,
            'description' => $data['description'],
        ]);

        $updatedFields = array_keys($team->getChanges());
        unset($updatedFields['updated_at']);

        if (empty($updatedFields)) {
            return response()->json(['success' => true], 304);
        }

        ApigeeService::updateCompany($team);

        foreach ($team->users as $user) {
            if ($oldName !== $team->name) {
                Notification::create([
                    'user_id' => $user->id,
                    'notification' => "Your team <strong> {$oldName} </strong> has been updated to <strong>{$team->name}</strong>, please click <a href='/teams/{$team->id}/team'>here</a> to navigate to your team to view the changes",
                ]);
            } else {
                Notification::create([
                    'user_id' => $user->id,
                    'notification' => "Your team <strong>{$team->name}</strong> has been updated please click <a href='/teams/{$team->id}/team'>here</a> to navigate to your team to view the changes",
                ]);
            }
        }

        return response()->json(['success' => true], 200);
    }
}
