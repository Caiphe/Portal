<?php

namespace App\Services;

use App\User;
use App\Factory\Teams\TeamFactory;

use Illuminate\Database\Eloquent\Collection;

/**s
 * Class TeamCompanyService
 *
 * @package App\Services
 */
class TeamCompanyService
{
    /**
     * @var $apigeeServices
     */
    protected $apigeeService;

    /**
     * Constructor method
     */
    public function __construction()
    {
        $this->apigeeService = new ApigeeService();
    }

    /**
     * List Company Apps
     *
     * @param $companyName
     * @return mixed
     */
    public function listCompanyApps(string $companyName)
    {
        return $this->apigeeService->get("/companies/{$companyName}/apps?expand=true");
    }

    /**
     * Create Dev Team owned App
     *
     * @param string $teamOwnerDevId
     * @param array $data
     * @return mixed
     */
    public function devOwnedAppCreate(string $teamOwnerDevId, array $data)
    {
        return $this->apigeeService->post("/developers/{$teamOwnerDevId}/apps", $data);
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
