<?php

namespace App\Services;

use App\User;
use App\Factory\Teams\TeamFactory;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Collection;

/**s
 * Class TeamCompanyService
 *
 * @package App\Services
 */
class TeamCompanyService extends ApigeeService
{
    /**
     * List Company Apps
     *
     * @param $companyName
     * @return mixed
     */
    public function listCompanyApps(string $companyName)
    {
        return static::get("/companies/{$companyName}/apps?expand=true");
    }

    /**
     * Create Dev Team owned App
     *
     * @param User $owner
     * @param array $input
     */
    public function createDeveloperTeam(User $owner, array $input)
    {
        $data = [
            "name" => Str::slug($input['name']),
            "displayName" => $input['name'],
            'apiProducts' => [],
            'keyExpiresIn' => -1,
            "attributes" => [
                [
                    "name" => "TeamName",
                    "value" => $input['name']
                ],[
                    "name" => "TeamOwner",
                    "value" => $owner->full_name
                ],[
                    "name" => "TeamDevId",
                    "value" => $owner->developer_id
                ],[
                    "name" => "OwnerEmail",
                    "value" => $owner->email
                ],[
                    "name" => "Country",
                    "value" => $input['country']
                ], [
                    "name" => "Notes",
                    "value" => ""
                ]
            ],
            'callbackUrl' => "",
        ];

        return static::createApp($data, $owner);
    }

    /**
     * Delete keys from company App
     *
     * @param string $teamOwnerDevId
     * @param string $companyName
     * @return mixed
     */
    public function deleteCompanyAppKeys(string $teamOwnerDevId, string $companyName)
    {
        return $this->apigeeService->post("/developers/{$teamOwnerDevId}/apps/{$companyName}/keys/create");
    }

    /**
     * Import company app keys into new app
     *
     * @param string $teamOwnerDevId
     * @param string $companyName
     * @return mixed
     */
    public function importCompanyAppKeys(string $teamOwnerDevId, string $companyName)
    {
        return $this->apigeeService->post("/developers/{$teamOwnerDevId}/apps/{$companyName}/keys/create");
    }

    /**
     * Add Product(s) to API Key
     *
     * @param string $teamOwnerDevId
     * @param string $companyName
     * @param string $key
     * @param array $products
     * @return mixed
     */
    public function addProductKeys(string $teamOwnerDevId, string $companyName, string $key, array $products)
    {
        return $this->apigeeService->post("/developers/{$teamOwnerDevId}/apps/{$companyName}/keys/{$key}", [
            'apiProducts' => $products
        ]);
    }

    /**
     * Delete API Key generated when creating new App
     *
     * @param string $teamOwnerDevId
     * @param string $companyName
     * @param string $key
     * @return mixed
     */
    public function deleteCompanyAppApiKeys(string $teamOwnerDevId, string $companyName, string $key)
    {
        return $this->apigeeService->delete("/developers/{$teamOwnerDevId}/apps/{$companyName}/keys/{$key}");
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
        $team = TeamFactory::createUserTeam($user, $data);

        $user->attachTeam($team);

        return $user->teams()
            ->where('id', $team->id)
            ->get();
    }
}
