<?php

namespace App\Services;

use App\User;
use App\Team;

use Illuminate\Http\RedirectResponse;
use App\Http\Requests\CreateAppRequest;
use Illuminate\Database\Eloquent\Collection;

/**s
 * Class TeamsService
 *
 * @package App\Services
 */
class TeamsService
{
    public function listCompanyApps()
    {

    }
    
    public function storeOrUpdateApp(CreateAppRequest $request, array $team, User $currentUser = null)
    {
        $validated = $request->all();
        $currentUser ??= $request->user();

        $now = date('Y-m-d H:i:s');
        $isUpdating = isset($app['aid']);

        $products = $credentials = [];

        $name = $validated['name'];

        $data = [
            'name' => Str::slug($name),
            'apiProducts' => $products,
            'keyExpiresIn' => -1,
            'attributes' => [
                [
                    'name' => 'DisplayName',
                    'value' => $name,
                ],
                [
                    'name' => 'Description',
                    'value' => $validated['description'],
                ],
                [
                    'name' => 'Team',
                    'value' => $team['name'],
                ],
            ],
            'callbackUrl' => $validated['url'],
        ];

        $createdResponse = null;

        if ($isUpdating) {

            $data['originalName'] = $app->name;
            $data['originalProducts'] = $app->products->pluck('name')->toArray();

            $data['key'] = $this->getCredentials($app, 'consumerKey');

            $createdResponse = ApigeeService::updateApp($data);
        } else {

            $createdResponse = ApigeeService::createApp($data);

            if (str_contains($createdResponse, 'duplicate key')) {
                return redirect()->back()->with('alert', 'Error:There is already an app with that name.')->withInput();
            }

            $credentials = ApigeeService::getLatestCredentials($createdResponse['credentials']);

            unset($credentials['apiProducts']);
        }

        $attributes = ApigeeService::getAppAttributes($createdResponse['attributes']);

        if ($isUpdating) {
            $app->update([
                "display_name" => $name,
                "callback_url" => $data['callbackUrl'],
                "description" => $data['attributes'][1]['value'],
                "attributes" => $attributes,
            ]);

            $app->products()->sync($products);

            if ($request->get('response', '') === 'json') return $app;

            return redirect()->route('app.index')->with('alert', 'success:Your app was updated');
        } else {
            $app = App::create([
                "aid" => $createdResponse['appId'],
                "name" => $createdResponse['name'],
                "display_name" => $name,
                "callback_url" => $createdResponse['callbackUrl'],
                "attributes" => $attributes,
                "credentials" => $credentials,
                "developer_id" => $currentUser->developer_id,
                "company_username" => $createdResponse['companyName'] ?? null,
                "status" => $createdResponse['status'],
                "description" => $attributes['Description'] ?? '',
                "country_id" => 0,
                "updated_at" => date('Y-m-d H:i:s', $createdResponse['lastModifiedAt'] / 1000),
                "created_at" => date('Y-m-d H:i:s', $createdResponse['createdAt'] / 1000),
            ]);

            $app->products()->sync($products);

            if ($request->get('response', '') === 'json') return $app;

            return redirect()->route('app.index')->with('alert', 'success:Your app was created');
        }
    }

    public function getCredentials(App $app, $type)
    {
        if (auth()->user()->developer_id !== $app->developer_id) return "You can't access app keys that don't belong to you.";

        $credentials = ApigeeService::get('apps/' . $app->aid)['credentials'];

        $credentials = ApigeeService::getLatestCredentials($credentials);

        return response($credentials, 200)
            ->header('Content-Type', 'text/plain');
    }

    /**
     * A User associated newly created Team object
     *
     * @param User $user
     * @param array $data
     * @return Team
     */
    public static function createTeam(User $user, array $data): Team
    {
        return Team::create([
            'name' => $data['name'],
            'url' => $data['url'],
            'contact' => $data['contact'],
            'country' => $data['country'],
            'description' => $data['description'],
            'logo' => $data['filename'],
            'owner_id' => $user->getKey()
        ]);
    }

    /**
     * Add Team to local storage, in this case, RDBMS Server
     *
     * @param User $user
     * @param array $data
     * @return Collection
     */
    public function createUserTeam(User $user, array $data): Collection
    {
        $team = static::createTeam($user, $data);

        $user->attachTeam($team);

        return $user->teams()
            ->where('id', $team->id)
            ->get();
    }

    /**
     * @param $request
     * @return array|RedirectResponse
     */
    public function handleFileUpload($request): array|RedirectResponse
    {
        $logoFileData = [];

        if($request->hasFile('logo')){

            $logoFileData = $this->transferClientLogo($request->file('logo'));

            if (!$logoFileData['success']) {
                return redirect()->back()->with('alert', "error:Your Team's logo file could not be created. Please try again.");
            }
        }

        return $logoFileData;
    }

    /**
     * Stores this file within the storage folder
     *
     * @param $logoFile
     * @return array
     */
    private function transferClientLogo($logoFile): array
    {
        $clientFileName = $logoFile->getClientOriginalName();

        $fileName = pathinfo($clientFileName, PATHINFO_FILENAME);
        $fileExtension = $logoFile->getClientOriginalExtension();

        $newFileName = $fileName . '_' . time(). '.' . $fileExtension;

        $fileSaved = $logoFile->storeAs('public/companies', $newFileName);

        return ['success' => $fileSaved, 'filename' => $newFileName];
    }
}
